<?php

namespace CodeWithDennis\FilamentResourceTests\Commands;

use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

use function Laravel\Prompts\select;

class FilamentResourceTestsCommand extends Command
{
    protected $signature = 'make:filament-resource-test {name?} {--outputName=}';

    protected $description = 'Create a new test for a Filament resource.';

    protected ?string $resourceName = '';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected function getOutputName(): string
    {
        return $this->option('outputName') ?? $this->resourceName;
    }

    protected function getStubPath(): string
    {
        return __DIR__.'/../../stubs/Resource.stub';
    }

    protected function getStubVariables(): array
    {
        $name = $this->resourceName;
        $singularName = str($name)->singular()->remove('resource', false);
        $pluralName = str($name)->plural()->remove('resource', false);

        return [
            'resource' => $this->getResourceName(),
            'model' => $this->getModel(),
            'singular_name' => $singularName,
            'singular_name_lowercase' => $singularName->lower(),
            'plural_name' => $pluralName,
            'plural_name_lowercase' => $pluralName->lower(),
        ];
    }

    protected function getSourceFile(): array|bool|string
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    protected function getStubContents($stub, $stubVariables = []): array|bool|string
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$'.$search.'$', $replace, $contents);
        }

        return $contents;
    }

    protected function getSourceFilePath(): string
    {
        $directory = trim(config('filament-resource-tests.directory_name'), '/');

        if (config('filament-resource-tests.separate_tests_into_folders')) {
            $directory .= DIRECTORY_SEPARATOR.$this->resourceName;
        }

        $outputName = $this->getOutputName();

        return $directory.DIRECTORY_SEPARATOR.$outputName.'Test.php';
    }

    protected function makeDirectory($path): string
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    protected function getModel(): ?string
    {
        return $this->getResourceClass()?->getModel();
    }

    protected function getResourceName(): ?string
    {
        return str($this->resourceName)->endsWith('Resource') ?
            $this->resourceName :
            $this->resourceName.'Resource';
    }

    protected function getResourceClass(): ?Resource
    {
        $match = $this->getResources()
            ->first(fn ($resource): bool => str_contains($resource, $this->getResourceName()) && class_exists($resource));

        return $match ? app()->make($match) : null;
    }

    protected function getResources(): Collection
    {
        return collect(Filament::getResources());
    }

    protected function getTable(): Table
    {
        $livewire = app('livewire')->new(ListRecords::class);

        return $this->getResourceClass()::table(new Table($livewire));
    }

    protected function getResourceTableColumns(): array
    {
        return $this->getTable()->getColumns();
    }

    protected function getResourceSortableTableColumns(): Collection
    {
        return collect($this->getResourceTableColumns())->filter(fn ($column) => $column->isSortable());
    }

    protected function getResourceSearchableTableColumns(): Collection
    {
        return collect($this->getResourceTableColumns())->filter(fn ($column) => $column->isSearchable());
    }

    protected function getResourceTableFilters(): array
    {
        return $this->getTable()->getFilters();
    }

    public function handle(): int
    {
        // Get the resource name from the command argument
        $this->resourceName = $this->argument('name');

        // Get all available resources
        $availableResources = $this->getResources()
            ->map(fn ($resource): string => str($resource)->afterLast('Resources\\'));

        // Ask the user for the resource
        $this->resourceName = (string) str(
            $this->resourceName ?? select(
                label: 'What is the resource you would like to create this test for?',
                options: $availableResources->flatten(),
                required: true,
            ),
        )
            ->studly()
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        // If the resource does not end with 'Resource', append it
        if (! str($this->resourceName)->endsWith('Resource')) {
            $this->resourceName .= 'Resource';
        }

        // Check if the resource exists
        if (! $this->getResourceClass()) {
            $this->warn("The filament resource {$this->resourceName} does not exist.");

            return self::FAILURE;
        }

        // Get the source file path
        $path = $this->getSourceFilePath();

        // Make the directory if it does not exist
        $this->makeDirectory(dirname($path));

        // Get the source file contents
        $contents = $this->getSourceFile();

        // Check if the test already exists
        if ($this->files->exists($path)) {

            $outputNameOption = $this->option('outputName');

            $message = "A test for {$this->getResourceName()}";

            if ($outputNameOption !== null) {
                $message .= " ({$outputNameOption})";
            }

            $message .= ' already exists.';
            $this->warn($message);

            return self::FAILURE;
        }

        // Write the file
        $this->files->put($path, $contents);

        // Output success message
        $this->info("A test for {$this->getResourceName()} created successfully.");

        return self::SUCCESS;
    }
}
