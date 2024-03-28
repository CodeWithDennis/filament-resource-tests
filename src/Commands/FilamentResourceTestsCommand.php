<?php

namespace CodeWithDennis\FilamentResourceTests\Commands;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;

class FilamentResourceTestsCommand extends Command
{
    protected $signature = 'make:filament-resource-test';

    protected $description = 'Create tests for a Filament components';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle(): int
    {
        $availableResources = $this->getResources()
            ->map(fn ($resource): string => str($resource)->afterLast('Resources\\'));

        $selectedResources = multiselect(
            label: 'What is the resource you would like to create this test for?',
            options: $availableResources->flatten(),
            required: true,
        );

        // Check if the first selected item is numeric (on windows without WSL multiselect returns an array of numeric strings)
        if (is_numeric($selectedResources[0] ?? null)) {
            // Convert the indexed selection back to the original resource path => resource name
            $selectedResources = collect($selectedResources)
                ->mapWithKeys(fn ($index) => [
                    $availableResources->keys()->get($index) => $availableResources->get($availableResources->keys()->get($index)),
                ]);
        }

        foreach ($selectedResources as $selectedResource) {
            $resource = $this->getResourceClass($selectedResource);

            $path = $this->getSourceFilePath($selectedResource);

            $this->makeDirectory(dirname($path));

            $contents = $this->getSourceFile($resource);

            if ($this->files->exists($path)) {
                // If the file already exists, ask the user if they want to overwrite it.
                if (! confirm("Test for {$selectedResource} already exists. Do you want to overwrite it?")) {
                    continue;
                }
            }

            $this->files->put($path, $contents);

            $this->info("Test for {$selectedResource} created successfully.");
        }

        return self::SUCCESS;
    }

    protected function getResources(): Collection
    {
        return collect(Filament::getResources());
    }

    protected function getResourceClass(string $resource): ?Resource
    {
        $match = $this->getResources()
            ->first(fn ($value): bool => str_contains($value, $resource) && class_exists($value));

        return $match ? app()->make($match) : null;
    }

    protected function getSourceFilePath(string $name): string
    {
        $directory = trim(config('filament-resource-tests.directory_name'), '/');

        if (config('filament-resource-tests.separate_tests_into_folders')) {
            $directory .= DIRECTORY_SEPARATOR.$name;
        }

        return $directory.DIRECTORY_SEPARATOR.$name.'Test.php';
    }

    protected function makeDirectory($path): string
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    protected function getSourceFile(Resource $resource): array|bool|string
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables($resource));
    }

    protected function getStubContents(string $stub, array $stubVariables = []): array|bool|string
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$'.$search.'$', $replace, $contents);
        }

        return $contents;
    }

    protected function getStubPath(): string
    {
        return __DIR__.'/../../stubs/Resource.stub';
    }

    protected function getStubVariables(Resource $resource): array
    {
        $resourceModel = $resource->getModel();
        $columns = collect($this->getResourceTable($resource)->getColumns());

        $userModel = User::class;
        $modelImport = $resourceModel === $userModel ? "use {$resourceModel};" : "use {$resourceModel};\nuse {$userModel};";

        $individualSearchableColumns = $columns->filter(fn ($column) => $column->isIndividuallySearchable())->keys();

        $modelSingularName = str($resourceModel)->afterLast('\\');

        return [
            'resource' => str($resource::class)->afterLast('\\'),
            'modelImport' => $modelImport,
            'modelSingularName' => $modelSingularName,
            'modelPluralName' => str($resourceModel)->afterLast('\\')->plural(),
            'resourceTableColumns' => $this->convertDoubleQuotedArrayString($columns->keys()),
            'resourceTableColumnsWithoutHidden' => $this->convertDoubleQuotedArrayString($columns->filter(fn ($column) => ! $column->isToggledHiddenByDefault())->keys()),
            'resourceTableToggleableColumns' => $this->convertDoubleQuotedArrayString($columns->filter(fn ($column) => $column->isToggleable())->keys()),
            'resourceTableSortableColumns' => $this->convertDoubleQuotedArrayString($columns->filter(fn ($column) => $column->isSortable())->keys()),
            'resourceTableSearchableColumns' => $this->convertDoubleQuotedArrayString($columns->filter(fn ($column) => $column->isSearchable())->keys()),
            'resourceTableIndividuallySearchableColumns' => ! $individualSearchableColumns->isEmpty() ?
                $this->convertDoubleQuotedArrayString($columns->filter(fn ($column) => $column->isIndividuallySearchable())->keys()) :
                '\''.$modelSingularName.' does not have individually searchable columns\'',
            'resourceTableIndividuallySearchableColumnsMethod' => $individualSearchableColumns->isEmpty() ? 'skip' : 'with',
            'resourceTableIndividuallySearchableColumnsVariable' => ! $individualSearchableColumns->isEmpty() ? 'string $column' : '',
        ];
    }

    protected function getResourceTable(Resource $resource): Table
    {
        $livewire = app('livewire')->new(ListRecords::class);

        return $resource::table(new Table($livewire));
    }

    protected function convertDoubleQuotedArrayString(string $string): string
    {
        return str($string)
            ->replace('"', '\'')
            ->replace(',', ', ');
    }

    protected function getResourceTableColumns(Table $table): array
    {
        return $table->getColumns();
    }

    protected function getResourceTableFilters(Table $table): array
    {
        return $table->getFilters();
    }
}
