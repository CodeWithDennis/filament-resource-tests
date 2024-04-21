<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Description extends Base
{
    public function getDescription(): string
    {
        return 'has the correct table description';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index')
            && $this->tableHasDescription();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_DESCRIPTION' => $this->convertDoubleQuotedArrayString(str($this->getTableDescription())->wrap('\'')),
        ];
    }
}
