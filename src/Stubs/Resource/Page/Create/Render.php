<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Render extends Base
{
    public function getDescription(): string
    {
        return 'can render the create page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('create');
    }
}
