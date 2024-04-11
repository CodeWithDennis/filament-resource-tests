<?php

namespace CodeWithDennis\FilamentTests\Stubs\Page\Index\Table\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class DeleteSoft extends Base
{
    public function getShouldGenerate(): bool
    {
        return $this->hasTableAction('delete', $this->resource) && $this->hasSoftDeletes($this->resource);
    }
}
