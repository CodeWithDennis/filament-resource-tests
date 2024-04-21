<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns;

use CodeWithDennis\FilamentTests\Stubs\Base;

class DescriptionAbove extends Base
{
    public function getDescription(): string
    {
        return 'has the correct descriptions above';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getDescriptionAboveColumns()->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_DESCRIPTIONS_ABOVE_COLUMNS' => $this->transformToPestDataset($this->getTableColumnDescriptionAbove(), ['column', 'description']),
        ];
    }
}
