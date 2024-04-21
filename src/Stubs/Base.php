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

    public static function make(?Resource $resource = null): self
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
        ];

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

    public function getResourceRequiredCreateFields(?Resource $resource = null): Collection
    {
        return collect($this->getResourceCreateForm($resource ?? $this->resource)->getFlatFields())
            ->filter(fn ($field) => $field->isRequired());
    }

    public function getResourceRequiredEditFields(?Resource $resource = null): Collection
    {
        return collect($this->getResourceEditForm($resource ?? $this->resource)->getFlatFields())
            ->filter(fn ($field) => $field->isRequired());
    }

    public function getResourceCreateFields(?Resource $resource = null): array
    {
        return $this->getResourceCreateForm($resource ?? $this->resource)->getFlatFields(withHidden: true);
    }

    public function getResourceEditFields(?Resource $resource = null): array
    {
        return $this->getResourceEditForm($resource ?? $this->resource)->getFlatFields(withHidden: true);
    }

    public function getResourceEditForm(?Resource $resource = null): Form
    {
        $resource = $resource ?? $this->resource;

        $livewire = app('livewire')->new(EditRecord::class);

        return $resource::form(new Form($livewire));
    }

    public function getResourceCreateForm(?Resource $resource = null): Form
    {
        $resource = $resource ?? $this->resource;

        $livewire = app('livewire')->new(CreateRecord::class);

        return $resource::form(new Form($livewire));
    }

    public function getResourceTable(?Resource $resource = null): Table
    {
        $resource = $resource ?? $this->resource;

        $livewire = app('livewire')->new(ListRecords::class);

        return $resource::table(new Table($livewire));
    }

    public function getResourcePages(?Resource $resource = null): Collection
    {
        $resource = $resource ?? $this->resource;

        return collect($resource::getPages())->keys();
    }

    public function hasPage(string $name, ?Resource $resource = null): bool
    {
        return $this->getResourcePages($resource ?? $this->resource)->contains($name);
    }

    public function tableHasPagination(?Resource $resource = null): bool
    {
        return $this->getResourceTable($resource ?? $this->resource)->isPaginated();
    }

    public function tableHasHeading(?Resource $resource = null): bool
    {
        return $this->getResourceTable($resource ?? $this->resource)->getHeading() !== null;
    }

    public function getTableHeading(?Resource $resource = null): ?string
    {
        return $this->getResourceTable($resource ?? $this->resource)->getHeading();
    }

    public function tableHasDescription(?Resource $resource = null): bool
    {
        return $this->getResourceTable($resource ?? $this->resource)->getDescription() !== null;
    }

    public function getTableDescription(?Resource $resource = null): ?string
    {
        return $this->getResourceTable($resource ?? $this->resource)->getDescription();
    }

    public function getTableDefaultPaginationPageOption(?Resource $resource = null): int|string|null
    {
        return $this->getResourceTable($resource ?? $this->resource)->getDefaultPaginationPageOption();
    }

    public function getTableColumns(?Resource $resource = null): Collection
    {
        return collect($this->getResourceTable($resource ?? $this->resource)->getColumns());
    }

    public function getSearchableColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isSearchable());
    }

    public function getSortableColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isSortable());
    }

    public function getIndividuallySearchableColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isIndividuallySearchable());
    }

    public function getToggleableColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isToggleable());
    }

    public function getToggledHiddenByDefaultColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->isToggledHiddenByDefault());
    }

    public function getInitiallyVisibleColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => ! $column->isToggledHiddenByDefault());
    }

    public function getDescriptionAboveColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => method_exists($column, 'description') &&
                $column->getDescriptionAbove()
            );
    }

    public function getDescriptionBelowColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => method_exists($column, 'description') &&
                $column->getDescriptionBelow()
            );
    }

    public function getTableColumnDescriptionAbove(?Resource $resource = null): array
    {
        return $this->getDescriptionAboveColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'description' => $column->getDescriptionAbove(),
            ])->toArray();
    }

    public function getTableColumnDescriptionBelow(?Resource $resource = null): array
    {
        return $this->getDescriptionBelowColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'description' => $column->getDescriptionBelow(),
            ])->toArray();
    }

    public function getExtraAttributesColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column->getExtraAttributes());
    }

    public function getExtraAttributesColumnValues(?Resource $resource = null): array
    {
        return $this->getExtraAttributesColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'attributes' => $column->getExtraAttributes(),
            ])->toArray();
    }

    public function getTableSelectColumns(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource)
            ->filter(fn ($column) => $column instanceof \Filament\Tables\Columns\SelectColumn);
    }

    public function getTableSelectColumnsWithOptions(?Resource $resource = null): array
    {
        return $this->getTableSelectColumns($resource)
            ->map(fn ($column) => [
                'column' => $column->getName(),
                'options' => $column->getOptions(),
            ])->toArray();
    }

    public function getResourceTableColumnsWithSummarizers(?Resource $resource = null): Collection
    {
        return $this->getTableColumns($resource ?? $this->resource)->filter(fn ($column) => $column->getSummarizers());
    }

    public function hasSoftDeletes(?Resource $resource = null): bool
    {
        $resource = $resource ?? $this->resource;

        return method_exists($resource->getModel(), 'bootSoftDeletes');
    }

    public function getResourceTableActions(?Resource $resource = null): Collection
    {
        return collect($this->getResourceTable($resource ?? $this->resource)->getFlatActions());
    }

    public function getResourceTableBulkActions(?Resource $resource = null): Collection
    {
        return collect($this->getResourceTable($resource ?? $this->resource)->getFlatBulkActions());
    }

    public function getResourceTableFilters(Table $table): Collection
    {
        return collect($table->getFilters());
    }

    public function tableHasDeferredLoading(?Resource $resource = null): bool
    {
        return $this->getResourceTable($resource ?? $this->resource)->isLoadingDeferred();
    }

    public function getIndexHeaderActions(?Resource $resource = null): Collection
    {
        $resource = $resource ?? $this->resource;

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

    public function getTableActionNames(?Resource $resource = null): Collection
    {
        return $this->getResourceTableActions($resource ?? $this->resource)->map(fn ($action) => $action->getName());
    }

    public function getTableActionsWithUrl(?Resource $resource = null): Collection
    {
        return $this->getResourceTableActions($resource)
            ->filter(fn ($action) => $action->getUrl() && ! $action->shouldOpenUrlInNewTab());
    }

    public function getTableActionsWithUrlThatShouldOpenInNewTab(?Resource $resource = null): Collection
    {
        return $this->getResourceTableActions($resource)
            ->filter(fn ($action) => $action->getUrl() && $action->shouldOpenUrlInNewTab());
    }

    public function hasTableActionWithUrl(?Resource $resource = null): bool
    {
        return $this->getTableActionsWithUrl($resource ?? $this->resource)->isNotEmpty();
    }

    public function hasTableActionWithUrlThatShouldOpenInNewTab(?Resource $resource = null): bool
    {
        return $this->getTableActionsWithUrlThatShouldOpenInNewTab($resource ?? $this->resource)->isNotEmpty();
    }

    public function getTableActionsWithUrlNames(?Resource $resource = null): Collection
    {
        return $this->getTableActionsWithUrl($resource)
            ->map(fn ($action) => $action->getName());
    }

    public function getTableActionsWithUrlThatShouldOpenInNewTabNames(?Resource $resource = null): Collection
    {
        return $this->getTableActionsWithUrlThatShouldOpenInNewTab($resource ?? $this->resource)->map(fn ($action) => $action->getName());
    }

    public function getTableActionsWithUrlValues(?Resource $resource = null): array
    {
        return $this->getTableActionsWithUrl($resource ?? $this->resource)->map(fn ($action) => [
            'name' => $action->getName(),
            'url' => $action->getUrl(),
        ])->toArray();
    }

    public function getTableActionsWithUrlThatShouldOpenInNewTabValues(?Resource $resource = null): array
    {
        return $this->getTableActionsWithUrlThatShouldOpenInNewTab($resource ?? $this->resource)->map(fn ($action) => [
            'name' => $action->getName(),
            'url' => $action->getUrl(),
        ])->toArray();
    }

    public function hasTableAction(string $action, ?Resource $resource = null): bool
    {
        return $this->getResourceTableActions($resource ?? $this->resource)->map(fn ($action) => $action->getName())->contains($action);
    }

    public function hasAnyTableAction(array $actions, ?Resource $resource = null): bool
    {
        return $this->getResourceTableActions($resource ?? $this->resource)->map(fn ($action) => $action->getName())->intersect($actions)->isNotEmpty();
    }

    public function hasAnyTableBulkAction(array $actions, ?Resource $resource = null): bool
    {
        return $this->getResourceTableBulkActions($resource ?? $this->resource)->map(fn ($action) => $action->getName())->intersect($actions)->isNotEmpty();
    }

    public function hasAnyIndexHeaderAction(array $actions, ?Resource $resource = null): bool
    {
        return $this->getIndexHeaderActions($resource)['all']->intersect($actions)->isNotEmpty();
    }

    public function hasAnyHiddenIndexHeaderAction(array $actions, ?Resource $resource = null): bool
    {
        return $this->getIndexHeaderActions($resource)['hidden']->intersect($actions)->isNotEmpty();
    }

    public function hasAnyVisibleIndexHeaderAction(array $actions, ?Resource $resource = null): bool
    {
        return $this->getIndexHeaderActions($resource)['visible']->intersect($actions)->isNotEmpty();
    }

    public function hasTableBulkAction(string $action, ?Resource $resource = null): bool
    {
        return $this->getResourceTableBulkActions($resource ?? $this->resource)->map(fn ($action) => $action->getName())->contains($action);
    }

    public function getTableBulkActionNames(?Resource $resource = null): Collection
    {
        return $this->getResourceTableBulkActions($resource ?? $this->resource)->map(fn ($action) => $action->getName());
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
            ->map(fn ($part) => "'".trim(str($part)->kebab())."'");

        return $this->convertDoubleQuotedArrayString($parts->implode(','));
    }
}
