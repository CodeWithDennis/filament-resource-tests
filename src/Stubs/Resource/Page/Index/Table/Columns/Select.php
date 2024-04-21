<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Select extends Base
{
    public function getDescription(): string
    {
        return 'has the correct options';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getTableSelectColumns()->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_SELECT_COLUMNS' => $this->transformToPestDataset($this->getTableSelectColumnsWithOptions(), ['column', 'options']),
        ];
    }
}
