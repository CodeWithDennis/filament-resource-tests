<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Replicate extends Base
{
    public function getDescription(): string
    {
        return 'can replicate records';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasTableAction('replicate');
    }
}
