<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Delete extends Base
{
    public function getDescription(): string
    {
        return 'can delete records';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasTableAction('delete') && ! $this->hasSoftDeletes();
    }
}
