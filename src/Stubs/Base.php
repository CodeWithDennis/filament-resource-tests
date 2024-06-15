<?php

namespace CodeWithDennis\FilamentTests\Stubs;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class Base
{
    use EvaluatesClosures;

    public Closure|string|null $group = null;

    public Closure|string|null $name = null;

    public Closure|string $description = '';

    public Closure|string|null $path;

    public Closure|bool|null $shouldGenerate = true;

    public Closure|array|null $variables;

    public Closure|bool $isTodo = false;

    public Closure|bool $shouldGenerateWithTodos = true;

    public function __construct(public Resource $resource, public ?string $relationManager = null)
    {
    }

    public static function make(Resource $resource, ?string $relationManager = null): static
    {
        return new static($resource, $relationManager);
    }

    public function group(string|Closure|null $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function resolveGroupByNamespace(): ?string
    {
        $namespace = get_class($this);

        if (! str_contains($namespace, 'Stubs\\')) {
            return null;
        }

        $partAfterStubs = str($namespace)->after('Stubs\\');

        if (! $partAfterStubs->contains('\\')) {
            return null;
        }

        return $partAfterStubs->beforeLast('\\')->replace('\\', '/');
    }

    public function getGroup(): ?string
    {
        return $this->evaluate($this->group ?? $this->resolveGroupByNamespace());
    }

    public function path(string|Closure|null $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function resolveNameByClass(): string
    {
        $class = get_class($this);

        return str($class)->afterLast('\\');
    }

    public function name(string|Closure|null $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->evaluate($this->name ?? $this->resolveNameByClass());
    }

    public function description(string|Closure $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->evaluate($this->description ?? '');
    }

    public function getPath(): string
    {
        $path = __DIR__.'/../../stubs/'.$this->getGroup().'/'.$this->getName().'.stub';

        $default = str($path)
            ->replaceMatches('/[\/\\\\]+/', DIRECTORY_SEPARATOR);

        return $this->evaluate($this->path ?? $default);
    }

    public function variables(array|Closure|null $variables): static
    {
        $this->variables = $variables;

        return $this;
    }

    public function getVariables(): array
    {
        return $this->evaluate($this->variables ?? []);
    }

    public function getResourceClass($resource, $page): string
    {
        return $this->hasPage($page, $resource)
            ? '\\'.$this->getResourcePages($resource)->get($page)?->getPage().'::class'
            : '';
    }

    public function getDefaultVariables(): array
    {
        $resource = $this->resource;
        $resourceClass = $resource::class;
        $resourceModel = $resource->getModel();
        $resourceModelName = str($resourceModel)->afterLast('\\');
        $userModel = \App\Models\User::class;

        $modelImport = "use {$resourceModel};".($resourceModel !== $userModel ? "\nuse {$userModel};" : '');

        $getResourceClass = fn ($page, $isPlural = false) => str("\\{$resourceClass}\\Pages\\{$page}".($isPlural ? $resourceModelName->plural() : $resourceModelName).'::class')->replace('/', '\\');

        $toBeConverted = [
            'DESCRIPTION' => str($this->getDescription())->wrap('\''),
            'MODEL_IMPORT' => $modelImport,
            'MODEL_PLURAL_NAME' => $resourceModelName->plural(),
            'MODEL_SINGULAR_NAME' => $resourceModelName,

            'RESOURCE_LIST_CLASS' => $this->getResourceClass($resource, 'index'),
            'RESOURCE_CREATE_CLASS' => $this->getResourceClass($resource, 'create'),
            'RESOURCE_EDIT_CLASS' => $this->getResourceClass($resource, 'edit'),
            'RESOURCE_VIEW_CLASS' => $this->getResourceClass($resource, 'view'),

            'LOAD_TABLE_METHOD_IF_DEFERRED' => $this->tableHasDeferredLoading($resource) ? $this->getDeferredLoadingMethod() : '',
            'RESOLVED_GROUP_METHOD' => $this->getGroupMethod(),
        ];

        return array_map([$this, 'convertDoubleQuotedArrayString'], $toBeConverted);
    }

    public function shouldGenerate(bool|Closure|null $condition): static
    {
        $this->shouldGenerate = $condition;

        return $this;
    }

    public function todo(bool|Closure $condition): static
    {
        $this->isTodo = $condition;

        return $this;
    }

    public function isTodo(): bool
    {
        return $this->evaluate($this->isTodo) ?? false;
    }

    public function shouldGenerateWithTodos(bool|Closure $condition): static
    {
        $this->shouldGenerateWithTodos = $condition;

        return $this;
    }

    public function getShouldGenerateWithTodos(): bool
    {
        return $this->evaluate($this->shouldGenerateWithTodos);
    }

    public function getGroupToConfig(bool $default = true): bool
    {
        $configKeys = collect(explode('/', $this->getGroup()))
            ->map(fn ($key) => str($key)->snake()->lower())
            ->prepend('generate')
            ->push(str($this->getName())->snake()->lower())
            ->implode('.');

        return config("filament-tests.{$configKeys}", $default);
    }

    public function getShouldGenerate(): bool
    {
        if ($this->isTodo() && $this->getShouldGenerateWithTodos()) {
            return true;
        }

        return $this->evaluate($this->shouldGenerate);
    }

    public function get(): ?array
    {
        if (! $this->getShouldGenerate()) {
            return null;
        }

        return [
            'name' => $this->getName(),
            'group' => $this->getGroup(),
            'fileName' => $this->getName().'.stub',
            'path' => $this->getPath(),
            'variables' => array_merge($this->getDefaultVariables(), $this->getVariables()),
            'isTodo' => $this->isTodo(),
        ];
    }

    public function convertDoubleQuotedArrayString(string $string): string
    {
        return str($string)
            ->replace('"', '\'')
            ->replace(',', ', ');
    }

    protected function transformToPestDataset(array $source, array $keys): string
    {
        $result = [];

        foreach ($source as $item) {
            $temp = [];

            foreach ($keys as $key) {
                if (isset($item[$key])) {
                    if (is_array($item[$key])) {
                        $nestedArray = [];
                        foreach ($item[$key] as $nestedKey => $nestedValue) {
                            $nestedArray[] = "'$nestedKey' => '$nestedValue'";
                        }
                        $temp[] = '['.implode(',', $nestedArray).']';
                    } else {
                        $temp[] = "'".$item[$key]."'";
                    }
                }
            }

            $result[] = '['.implode(',', $temp).']';
        }

        return $this->convertDoubleQuotedArrayString('['.implode(',', $result).']');
    }

    public function getResourceCreateFormRequiredFields(Resource $resource): Collection
    {
        return collect($this->getResourceCreateForm($resource)->getFlatFields())
            ->filter(fn ($field) => $field->isRequired());
    }

    public function getResourceEditFormRequiredFields(Resource $resource): Collection
    {
        return collect($this->getResourceEditForm($resource)->getFlatFields())
            ->filter(fn ($field) => $field->isRequired());
    }

    public function getResourceCreateFormFields(Resource $resource): Collection
    {
        return collect($this->getResourceCreateForm($resource)->getFlatFields(withHidden: true));
    }

    public function getResourceCreateFormVisibleFields(Resource $resource): Collection
    {
        return collect($this->getResourceCreateForm($resource)->getFlatFields());
    }

    public function getResourceCreateFormHiddenFields(Resource $resource): Collection
    {
        return $this->getResourceCreateFormFields($resource)
            ->diffKeys($this->getResourceCreateFormVisibleFields($resource));
    }

    public function getResourceEditFormFields(Resource $resource): Collection
    {
        return collect($this->getResourceEditForm($resource)->getFlatFields(withHidden: true));
    }

    public function getResourceEditFormVisibleFields(Resource $resource): Collection
    {
        return collect($this->getResourceEditForm($resource)->getFlatFields());
    }

    public function getResourceEditFormHiddenFields(Resource $resource): Collection
    {
        return $this->getResourceEditFormFields($resource)
            ->diffKeys($this->getResourceEditFormVisibleFields($resource));
    }

    public function getResourceEditForm(Resource $resource): Form
    {
        $livewire = app('livewire')->new(EditRecord::class);

        return $resource::form(new Form($livewire));
    }

    public function getResourceCreateForm(Resource $resource): Form
    {
        $livewire = app('livewire')->new(CreateRecord::class);

        return $resource::form(new Form($livewire));
    }

    public function getResourceTable(Resource $resource): Table
    {
        $livewire = app('livewire')->new(ListRecords::class);

        return $resource::table(new Table($livewire));
    }

    public function getResourceRelations(Resource $resource): Collection
    {
        return collect($resource::getRelations())
            ->map(fn ($relation) => ! is_string($relation) ? $relation->relationManager : $relation);
    }

    public function getResourceRelationManagerByName(string $name, Resource $resource): ?string
    {
        return $this->getResourceRelations($resource)
            ->filter(fn ($relation) => str($relation)->contains($name))
            ->first();
    }

    public function getRelationManagerTableColumns(?string $for = null): Collection
    {
        if (! $for) {
            return collect();
        }

        $table = $this->getRelationManagerTable($for);

        return collect($table->getColumns());
    }

    public function getRelationManagerInitiallyVisibleColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => ! $column->isToggledHiddenByDefault());
    }

    public function getRelationManagerToggledHiddenByDefaultColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => $column->isToggledHiddenByDefault());
    }

    public function getRelationManagerTable(string $for): Table
    {
        $livewire = app('livewire')->new(RelationManager::class);

        $relationClass = app()->make($this->getResourceRelationManagerByName($for, $this->resource));

        return $relationClass->table(new Table($livewire));
    }

    public function getRelationManager($for): RelationManager
    {
        $livewire = app('livewire')->new(RelationManager::class);

        return app()->make($this->getResourceRelationManagerByName($for, $this->resource));
    }

    public function getRelationManagerRelationshipNameToModelClass($for): string
    {
        $relationshipName = $this->getRelationManager($for)->getRelationshipName();

        return str($relationshipName)->singular()->ucfirst()->prepend('\App\\Models\\');
    }

    public function getResourcePages(Resource $resource): Collection
    {
        return collect($resource::getPages());
    }

    public function hasPage(string $name, Resource $resource): bool
    {
        return $this->getResourcePages($resource)->has($name);
    }

    public function tableHasPagination(Resource $resource): bool
    {
        return $this->getResourceTable($resource)->isPaginated();
    }

    public function tableHasHeading(Resource $resource): bool
    {
        return $this->getResourceTable($resource)->getHeading() !== null;
    }

    public function getTableHeading(Resource $resource): ?string
    {
        return $this->getResourceTable($resource)->getHeading();
    }

    public function tableHasDescription(Resource $resource): bool
    {
        return $this->getResourceTable($resource)->getDescription() !== null;
    }

    public function getTableDescription(Resource $resource): ?string
    {
        return $this->getResourceTable($resource)->getDescription();
    }

    public function getTableDefaultPaginationPageOption(Resource $resource): int|string|null
    {
        return $this->getResourceTable($resource)->getDefaultPaginationPageOption();
    }

    public function getTableColumns(Resource $resource): Collection
    {
        return collect($this->getResourceTable($resource)->getColumns());
    }

    public function getSearchableColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isSearchable());
    }

    public function getRelationManagerSearchableColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => $column->isSearchable());
    }

    public function getSortableColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isSortable());
    }

    public function getRelationManagerSortableColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => $column->isSortable());
    }

    public function getIndividuallySearchableColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isIndividuallySearchable());
    }

    public function getRelationManagerIndividuallySearchableColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => $column->isIndividuallySearchable());
    }

    public function getToggleableColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isToggleable());
    }

    public function getToggledHiddenByDefaultColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isToggledHiddenByDefault());
    }

    public function getInitiallyVisibleColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => ! $column->isToggledHiddenByDefault());
    }

    public function getDescriptionAboveColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => method_exists($column, 'description') &&
                $column->getDescriptionAbove()
            );
    }

    public function getRelationManagerDescriptionAboveColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => method_exists($column, 'description') &&
                $column->getDescriptionAbove()
            );
    }

    public function getRelationManagerDescriptionBelowColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => method_exists($column, 'description') &&
                $column->getDescriptionBelow()
            );
    }

    public function getDescriptionBelowColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => method_exists($column, 'description') &&
                $column->getDescriptionBelow()
            );
    }

    public function getTableColumnDescriptionAbove(Resource $resource): array
    {
        return $this->getDescriptionAboveColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'description' => $column->getDescriptionAbove(),
            ])->toArray();
    }

    public function getRelationManagerTableColumnDescriptionAbove(string $for): array
    {
        return $this->getRelationManagerDescriptionAboveColumns($for)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'description' => $column->getDescriptionAbove(),
            ])->toArray();
    }

    public function getRelationManagerTableColumnDescriptionBelow(string $for): array
    {
        return $this->getRelationManagerDescriptionBelowColumns($for)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'description' => $column->getDescriptionBelow(),
            ])->toArray();
    }

    public function getTableColumnDescriptionBelow(Resource $resource): array
    {
        return $this->getDescriptionBelowColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'description' => $column->getDescriptionBelow(),
            ])->toArray();
    }

    public function getExtraAttributesColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->getExtraAttributes());
    }

    public function getRelationManagerExtraAttributesColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => $column->getExtraAttributes());
    }

    public function getExtraAttributesColumnValues(Resource $resource): array
    {
        return $this->getExtraAttributesColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'attributes' => $column->getExtraAttributes(),
            ])->toArray();
    }

    public function getRelationManagerExtraAttributesColumnValues(string $for): array
    {
        return $this->getRelationManagerExtraAttributesColumns($for)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'attributes' => $column->getExtraAttributes(),
            ])->toArray();
    }

    public function getTableSelectColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column instanceof \Filament\Tables\Columns\SelectColumn);
    }

    public function getRelationManagerTableSelectColumns(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)
            ->filter(fn ($column) => $column instanceof \Filament\Tables\Columns\SelectColumn);
    }

    public function getTableSelectColumnsWithOptions(Resource $resource): array
    {
        return $this->getTableSelectColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'options' => $column->getOptions(),
            ])->toArray();
    }

    public function getRelationManagerTableSelectColumnsWithOptions(string $for): array
    {
        return $this->getRelationManagerTableSelectColumns($for)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'options' => $column->getOptions(),
            ])->toArray();
    }

    public function getResourceTableColumnsWithSummarizers(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)->filter(fn ($column) => $column->getSummarizers());
    }

    public function getRelationManagerTableColumnsWithSummarizers(string $for): Collection
    {
        return $this->getRelationManagerTableColumns($for)->filter(fn ($column) => $column->getSummarizers());
    }

    public function hasSoftDeletes(Resource $resource): bool
    {
        return method_exists($resource->getModel(), 'bootSoftDeletes');
    }

    public function getResourceTableActions(Resource $resource): Collection
    {
        return collect($this->getResourceTable($resource)->getFlatActions());
    }

    public function getResourceTableBulkActions(Resource $resource): Collection
    {
        return collect($this->getResourceTable($resource)->getFlatBulkActions());
    }

    public function getResourceTableFilters(Table $table): Collection
    {
        return collect($table->getFilters());
    }

    public function getRelationManagerTableFilters(string $for): Collection
    {
        return collect($this->getRelationManagerTable($for)->getFilters());
    }

    public function tableHasDeferredLoading(Resource $resource): bool
    {
        return $this->getResourceTable($resource)->isLoadingDeferred();
    }

    public function getIndexHeaderActions(Resource $resource): Collection
    {
        $defaults = [
            'all' => collect(),
            'visible' => collect(),
            'hidden' => collect(),
        ];

        $indexPage = $resource::getPages()['index'] ?? null;

        if (! $indexPage) {
            return collect($defaults);
        }

        try {
            $reflection = new \ReflectionClass($indexPage);

            $pageProperty = $reflection->getProperty('page');

            $page = $pageProperty->getValue($indexPage);

            $page = app()->make($page);

            $reflection = new \ReflectionClass($page);

            $getHeaderActionsProperty = $reflection->getMethod('getHeaderActions');

            $actions = $getHeaderActionsProperty->invoke($page);

            return collect([
                'all' => collect($actions)->map(fn ($action) => $action->getName()),

                'visible' => collect($actions)
                    ->filter(fn ($action) => $action->isVisible())
                    ->map(fn ($action) => $action->getName()),

                'hidden' => collect($actions)
                    ->filter(fn ($action) => ! $action->isVisible())
                    ->map(fn ($action) => $action->getName()),
            ]);
        } catch (\Throwable) {
            return collect($defaults);
        }
    }

    public function getTableActionNames(Resource $resource): Collection
    {
        return $this->getResourceTableActions($resource)->map(fn ($action) => $action->getName());
    }

    public function getTableActionsWithUrl(Resource $resource): Collection
    {
        return $this->getResourceTableActions($resource)
            ->filter(fn ($action) => $action->getUrl() && ! $action->shouldOpenUrlInNewTab());
    }

    public function getTableActionsWithUrlThatShouldOpenInNewTab(Resource $resource): Collection
    {
        return $this->getResourceTableActions($resource)
            ->filter(fn ($action) => $action->getUrl() && $action->shouldOpenUrlInNewTab());
    }

    public function hasTableActionWithUrl(Resource $resource): bool
    {
        return $this->getTableActionsWithUrl($resource)->isNotEmpty();
    }

    public function hasTableActionWithUrlThatShouldOpenInNewTab(Resource $resource): bool
    {
        return $this->getTableActionsWithUrlThatShouldOpenInNewTab($resource)->isNotEmpty();
    }

    public function getTableActionsWithUrlNames(Resource $resource): Collection
    {
        return $this->getTableActionsWithUrl($resource)
            ->map(fn ($action) => $action->getName());
    }

    public function getTableActionsWithUrlThatShouldOpenInNewTabNames(Resource $resource): Collection
    {
        return $this->getTableActionsWithUrlThatShouldOpenInNewTab($resource)->map(fn ($action) => $action->getName());
    }

    public function getTableActionsWithUrlValues(Resource $resource): array
    {
        return $this->getTableActionsWithUrl($resource)->map(fn ($action) => [
            'name' => $action->getName(),
            'url' => $action->getUrl(),
        ])->toArray();
    }

    public function getTableActionsWithUrlThatShouldOpenInNewTabValues(Resource $resource): array
    {
        return $this->getTableActionsWithUrlThatShouldOpenInNewTab($resource)->map(fn ($action) => [
            'name' => $action->getName(),
            'url' => $action->getUrl(),
        ])->toArray();
    }

    public function hasTableAction(string $action, Resource $resource): bool
    {
        return $this->getResourceTableActions($resource)->map(fn ($action) => $action->getName())->contains($action);
    }

    public function hasAnyTableAction(Resource $resource, array $actions): bool
    {
        return $this->getResourceTableActions($resource)->map(fn ($action) => $action->getName())->intersect($actions)->isNotEmpty();
    }

    public function hasAnyTableBulkAction(Resource $resource, array $actions): bool
    {
        return $this->getResourceTableBulkActions($resource)->map(fn ($action) => $action->getName())->intersect($actions)->isNotEmpty();
    }

    public function hasAnyIndexHeaderAction(Resource $resource, array $actions): bool
    {
        return $this->getIndexHeaderActions($resource)['all']->intersect($actions)->isNotEmpty();
    }

    public function hasAnyHiddenIndexHeaderAction(Resource $resource, array $actions): bool
    {
        return $this->getIndexHeaderActions($resource)['hidden']->intersect($actions)->isNotEmpty();
    }

    public function hasAnyVisibleIndexHeaderAction(Resource $resource, array $actions): bool
    {
        return $this->getIndexHeaderActions($resource)['visible']->intersect($actions)->isNotEmpty();
    }

    public function hasTableBulkAction(string $action, Resource $resource): bool
    {
        return $this->getResourceTableBulkActions($resource)->map(fn ($action) => $action->getName())->contains($action);
    }

    public function getTableBulkActionNames(Resource $resource): Collection
    {
        return $this->getResourceTableBulkActions($resource)->map(fn ($action) => $action->getName());
    }

    public function hasTableFilter(string $filter, Table $table): bool
    {
        return $this->getResourceTableFilters($table)->map(fn ($filter) => $filter->getName())->contains($filter);
    }

    public function getRegistrationRouteAction(): ?string
    {
        return Filament::getDefaultPanel()?->getRegistrationRouteAction();
    }

    public function hasRegistration(): bool
    {
        return Filament::hasRegistration();
    }

    public function getRequestPasswordResetRouteAction(): ?string
    {
        return Filament::getDefaultPanel()?->getRequestPasswordResetRouteAction();
    }

    public function hasPasswordReset(): bool
    {
        return Filament::hasPasswordReset();
    }

    public function getLoginRouteAction(): ?string
    {
        return Filament::getDefaultPanel()?->getLoginRouteAction();
    }

    public function getPanelPath(): ?string
    {
        return Filament::getDefaultPanel()?->getPath();
    }

    public function hasLogin(): bool
    {
        return Filament::hasLogin();
    }

    // TODO: implement
    public function hasRelationManagers(): bool
    {
        return true;
    }

    // TODO: implement
    public function hasInfolists(): bool
    {
        return true;
    }

    public function hasRelationManager(string $name): bool
    {
        return $this->getResourceRelations($this->resource)->contains($name);
    }

    public function relationManagerHasTableHeading(string $for): bool
    {
        return $this->getRelationManagerTable($for)->getHeading() !== null;
    }

    public function relationManagerHasTableDescription(string $for): bool
    {
        return $this->getRelationManagerTable($for)->getDescription() !== null;
    }

    public function getRelationManagerTableHeading(string $for): ?string
    {
        return $this->getRelationManagerTable($for)->getHeading();
    }

    public function getRelationManagerTableDescription(string $for): ?string
    {
        return $this->getRelationManagerTable($for)->getDescription();
    }

    // TODO: implement
    public function hasCustomPages(): bool
    {
        return false;
    }

    public function getDeferredLoadingMethod(): string
    {
        return "\n\t\t->loadTable()";
    }

    public function getGroupMethod(): string
    {
        return "->group({$this->toGroupMethod()})";
    }

    public function toGroupMethod(): ?string
    {
        $group = $this->getGroup();

        $parts = collect(explode('/', $group))
            ->filter(fn ($part) => ! empty($part))
            ->map(fn ($part) => "'".trim(str($part)->kebab())."'")
            ->sort();

        return $this->convertDoubleQuotedArrayString($parts->implode(','));
    }
}
