<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Sum extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can sum values in a column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getResourceTableColumnsWithSummarizers($this->resource)
            ->filter(fn ($column) => collect($column->getSummarizers())
                ->filter(fn ($summarizer) => $summarizer::class === \Filament\Tables\Columns\Summarizers\Sum::class)
                ->count())
            ->isNotEmpty();
    }
}
