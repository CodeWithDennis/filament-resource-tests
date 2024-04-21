<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkActions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Delete extends Base
{
    public function getDescription(): string
    {
        return 'can bulk delete records';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasTableFilter('trashed', $this->getResourceTable())
            && ! $this->hasSoftDeletes()
            && $this->hasTableBulkAction('delete');
    }
}
