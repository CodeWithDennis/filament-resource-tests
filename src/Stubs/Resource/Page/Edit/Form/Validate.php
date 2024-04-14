<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Validate extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can validate edit form input';
    }

    public function getShouldGenerate(): bool
    {
        // TODO: Implement getShouldGenerate() logic
        return true;
    }
}