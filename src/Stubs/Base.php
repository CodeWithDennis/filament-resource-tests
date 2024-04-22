<?php

namespace CodeWithDennis\FilamentTests\Stubs;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
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

    public function __construct(public Resource $resource)
    {
    }

    public static function make(Resource $resource): self
    {
        return new static($resource);
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

    public function getDefaultVariables(): array
    {
        $resource = $this->resource;

        $resourceModel = $resource->getModel();
        $userModel = \App\Models\User::class;
        $modelImport = $resourceModel === $userModel ? "use {$resourceModel};" : "use {$resourceModel};\nuse {$userModel};";

        $toBeConverted = [
            'DESCRIPTION' => str($this->getDescription())->wrap('\''),
            'MODEL_IMPORT' => $modelImport,
            'MODEL_PLURAL_NAME' => str($resourceModel)->afterLast('\\')->plural(),
            'MODEL_SINGULAR_NAME' => str($resourceModel)->afterLast('\\'),
            'RESOURCE' => str($resource::class)->afterLast('\\'),
            'RESOURCE_LIST_CLASS' => $this->hasPage('index', $resource) ? 'List'.str($resourceModel)->afterLast('\\')->plural()->append('::class') : '',
            'RESOURCE_CREATE_CLASS' => $this->hasPage('create', $resource) ? 'Create'.str($resourceModel)->afterLast('\\')->append('::class') : '',
            'RESOURCE_EDIT_CLASS' => $this->hasPage('edit', $resource) ? 'Edit'.str($resourceModel)->afterLast('\\')->append('::class') : '',
            'RESOURCE_VIEW_CLASS' => $this->hasPage('view', $resource) ? 'View'.str($resourceModel)->afterLast('\\')->append('::class') : '',
            'LOAD_TABLE_METHOD_IF_DEFERRED' => $this->tableHasDeferredLoading($resource) ? $this->getDeferredLoadingMethod() : '',
            'RESOLVED_GROUP_METHOD' => $this->getGroupMethod(),
            'FACTORY_FOR_TENANT_METHOD_IF_HAS_TENANCY' => $this->hasTenancy() ? $this->getFactoryForTenantMethod() : '',
        ];

        if ($this->hasTenancy()) {
            $toBeConverted['TENANT_MODEL'] = $this->convertDoubleQuotedArrayString($this->getTenantModel());
            $toBeConverted['TENANT_MODEL_CLASS'] = $this->convertDoubleQuotedArrayString($this->getTenantModelClass());
            $toBeConverted['TENANT_MODEL_PLURAL_NAME'] = str($this->getTenantModel())->afterLast('\\')->plural();
            $toBeConverted['TENANT_MODEL_PLURAL_NAME_LOWER'] = str($this->getTenantModel())->afterLast('\\')->plural()->lower();
            $toBeConverted['TENANT_MODEL_SINGULAR_NAME'] = str($this->getTenantModel())->afterLast('\\');
            $toBeConverted['TENANT_MODEL_SINGULAR_NAME_LOWER'] = str($this->getTenantModel())->afterLast('\\')->lower();
        }

        return array_map(function ($value) {
            return $this->convertDoubleQuotedArrayString($value);
        }, $toBeConverted);
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
            // removes additional white spaces that may have occurred during recursive conversions (transformToPestDataset)
            // TODO: potentially find a better way to handle this...what if the description has multiple spaces on purpose?
            ->replaceMatches('/\s*,\s*/', ', ');
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

    public function getResourceRequiredCreateFields(Resource $resource): Collection
    {
        return collect($this->getResourceCreateForm($resource)->getFlatFields())
            ->filter(fn ($field) => $field->isRequired());
    }

    public function getResourceRequiredEditFields(Resource $resource): Collection
    {
        return collect($this->getResourceEditForm($resource)->getFlatFields())
            ->filter(fn ($field) => $field->isRequired());
    }

    public function getResourceCreateFields(Resource $resource): array
    {
        return $this->getResourceCreateForm($resource)->getFlatFields(withHidden: true);
    }

    public function getResourceEditFields(Resource $resource): array
    {
        return $this->getResourceEditForm($resource)->getFlatFields(withHidden: true);
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

    public function getResourcePages(Resource $resource): Collection
    {
        return collect($resource::getPages())->keys();
    }

    public function hasPage(string $name, Resource $resource): bool
    {
        return $this->getResourcePages($resource)->contains($name);
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

    public function getSortableColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isSortable());
    }

    public function getIndividuallySearchableColumns(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)
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

    public function getExtraAttributesColumnValues(Resource $resource): array
    {
        return $this->getExtraAttributesColumns($resource)
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

    public function getTableSelectColumnsWithOptions(Resource $resource): array
    {
        return $this->getTableSelectColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'options' => $column->getOptions(),
            ])->toArray();
    }

    public function getResourceTableColumnsWithSummarizers(Resource $resource): Collection
    {
        return $this->getTableColumns($resource)->filter(fn ($column) => $column->getSummarizers());
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

    public function hasTenancy(): bool
    {
        return Filament::getDefaultPanel()->hasTenancy();
    }

    public function getTenantModel(): ?string
    {
        return Filament::getTenantModel();
    }

    public function getTenantModelClass(): ?string
    {
        return $this->getTenantModel() ? str($this->getTenantModel())->finish('::class') : null;
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

    public function getDeferredLoadingMethod(): string
    {
        return "\n\t\t->loadTable()";
    }

    public function getGroupMethod(): string
    {
        return "->group({$this->toGroupMethod()})";
    }

    public function getFactoryForTenantMethod(): string
    {
        return '->for($this->{{ TENANT_MODEL_SINGULAR_NAME_LOWER }})';
    }

    public function toGroupMethod(): ?string
    {
        $group = $this->getGroup();

        $parts = collect(explode('/', $group))
            ->filter(fn ($part) => ! empty($part))
            ->map(fn ($part) => "'".trim(str($part)->kebab())."'");

        $sortedParts = $parts->sort()->values();

        return $this->convertDoubleQuotedArrayString($sortedParts->implode(','));
    }
}
