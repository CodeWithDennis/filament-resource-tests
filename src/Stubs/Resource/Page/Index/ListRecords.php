<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index;

use CodeWithDennis\FilamentTests\Stubs\Base;

class ListRecords extends Base
{
    public function getDescription(): string
    {
        return 'can list records on the index page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index');
    }
}
