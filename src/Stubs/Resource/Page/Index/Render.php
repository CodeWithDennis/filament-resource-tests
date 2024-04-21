<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Render extends Base
{
    public function getDescription(): string
    {
        return 'can render the index page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index');
    }
}
