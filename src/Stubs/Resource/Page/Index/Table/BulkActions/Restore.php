<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkActions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Restore extends Base
{
    public function getDescription(): string
    {
        return 'can bulk restore records';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasTableFilter('trashed', $this->getResourceTable())
            && $this->hasSoftDeletes()
            && $this->hasTableBulkAction('restore');
    }
}
