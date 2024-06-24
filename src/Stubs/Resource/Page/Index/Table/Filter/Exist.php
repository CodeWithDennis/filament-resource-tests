<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filter;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Exist extends Base
{
    public function getDescription(): string
    {
        return 'has table filter';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->getResourceTableFilters($this->getResourceTable($this->resource))->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_FILTERS' => $this->convertDoubleQuotedArrayString($this->getResourceTableFilters($this->getResourceTable($this->resource))->keys()),
        ];
    }
}
