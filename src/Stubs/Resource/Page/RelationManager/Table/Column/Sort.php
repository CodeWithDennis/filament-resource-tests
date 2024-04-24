<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Sort extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can sort column on relation manager';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasRelationManagers();
    }
}
