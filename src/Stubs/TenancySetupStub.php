<?php

namespace CodeWithDennis\FilamentTests\Stubs;

use Closure;

class TenancySetupStub extends Base
{
    public Closure|string|null $name = 'TenancySetup';

    public function getShouldGenerate(): bool
    {
        if ($this->hasTenancy()) {
            return true;
        }

        return false;
    }
}
