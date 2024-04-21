<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Average extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can average values in a column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getResourceTableColumnsWithSummarizers()
            ->filter(fn ($column) => collect($column->getSummarizers())
                ->filter(fn ($summarizer) => $summarizer::class === \Filament\Tables\Columns\Summarizers\Average::class)
                ->count())
            ->isNotEmpty();
    }
}
