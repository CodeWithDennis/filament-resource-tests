<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Render extends Base
{
    public function getDescription(): string
    {
        return 'can render the edit page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('edit');
    }
}
