<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Render extends Base
{
    public function getDescription(): string
    {
        return 'can render column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getInitiallyVisibleColumns()->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_INITIALLY_VISIBLE_COLUMNS' => $this->convertDoubleQuotedArrayString($this->getInitiallyVisibleColumns()->keys()),
        ];
    }
}
