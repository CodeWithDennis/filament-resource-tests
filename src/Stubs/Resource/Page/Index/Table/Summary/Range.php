<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summary;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;
use ReflectionClass;

class Range extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can range values in a column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->getResourceTableColumnsWithSummarizers($this->resource)
                ->filter(fn ($column) => collect($column->getSummarizers())->filter(function ($summarizer) use ($column) {
                    if ($summarizer::class === \Filament\Tables\Columns\Summarizers\Range::class) {
                        $reflectionProperty = (new ReflectionClass(get_class($column)))
                            ->getProperty('isDate');

                        return ! $reflectionProperty->getValue($column);
                    }

                    return false;
                })->count())->isNotEmpty();
    }
}
