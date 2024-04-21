<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;
use Filament\Tables\Columns\IconColumn;

class CountIcon extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can count the occurrence of icons in a column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getResourceTableColumnsWithSummarizers()
            ->filter(fn ($column) => collect($column->getSummarizers())->filter(function ($summarizer) use ($column) {
                return $summarizer::class === \Filament\Tables\Columns\Summarizers\Count::class &&
                    $column::class === IconColumn::class;
            })->count())->isNotEmpty();
    }
}
