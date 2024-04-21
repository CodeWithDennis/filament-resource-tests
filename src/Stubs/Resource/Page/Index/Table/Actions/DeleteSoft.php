<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class DeleteSoft extends Base
{
    public function getDescription(): string
    {
        return 'can soft delete records';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasTableAction('delete') && $this->hasSoftDeletes();
    }
}
