<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\RelationManager\HeaderAction;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Exist extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'has header actions on the view page on '.str($this->getRelationManager($this->relationManager)->getRelationshipName())->lcfirst().' relation manager';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasRelationManagers();
    }
}
