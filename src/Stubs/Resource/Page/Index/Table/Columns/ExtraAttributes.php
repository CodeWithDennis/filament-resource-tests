<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns;

use CodeWithDennis\FilamentTests\Stubs\Base;

class ExtraAttributes extends Base
{
    public function getDescription(): string
    {
        return 'has extra attributes';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getExtraAttributesColumns()->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_EXTRA_ATTRIBUTES_COLUMNS' => $this->transformToPestDataset($this->getExtraAttributesColumnValues(), ['column', 'attributes']),
        ];
    }
}
