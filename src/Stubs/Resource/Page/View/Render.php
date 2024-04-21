<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\View;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Render extends Base
{
    public function getDescription(): string
    {
        return 'can render the view page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('view');
    }
}
