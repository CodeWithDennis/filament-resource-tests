<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Exist extends Base
{
    public function getDescription(): string
    {
        return 'has column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getTableColumns()->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_COLUMNS' => $this->convertDoubleQuotedArrayString($this->getTableColumns()->keys()),
        ];
    }
}
