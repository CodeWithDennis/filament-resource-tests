<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Trashed extends Base
{
    public function getDescription(): string
    {
        return 'cannot display trashed records by default';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasSoftDeletes() && $this->getTableColumns()->isNotEmpty();
    }
}
