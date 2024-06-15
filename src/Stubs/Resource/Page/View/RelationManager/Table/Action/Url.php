<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\RelationManager\Table\Action;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Url extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'has the correct URL for table action on the view page on '.str($this->getRelationManager($this->relationManager)->getRelationshipName())->lcfirst().' relation manager';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig(); // TODO: implement
    }
}