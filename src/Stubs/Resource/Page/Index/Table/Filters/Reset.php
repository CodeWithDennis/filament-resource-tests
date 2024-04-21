<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filters;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Reset extends Base
{
    public function getDescription(): string
    {
        return 'can reset table filters';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getResourceTableFilters($this->getResourceTable())->isNotEmpty();
    }
}
