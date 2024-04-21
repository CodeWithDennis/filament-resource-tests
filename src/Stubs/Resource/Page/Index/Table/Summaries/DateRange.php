<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;
use ReflectionClass;

class DateRange extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can range date values in a column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getResourceTableColumnsWithSummarizers()
            ->filter(fn ($column) => collect($column->getSummarizers())->filter(function ($summarizer) use ($column) {
                if ($summarizer::class === \Filament\Tables\Columns\Summarizers\Range::class) {
                    $reflectionProperty = (new ReflectionClass(get_class($column)))
                        ->getProperty('isDate');

                    return $reflectionProperty->getValue($column);
                }

                return false;
            })->count())->isNotEmpty();
    }
}
