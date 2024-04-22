<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index;

use CodeWithDennis\FilamentTests\Stubs\Base;

class TenancyListRecords extends Base
{
    public function getDescription(): string
    {
        return 'can list records on the index page (tenancy)';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index', $this->resource) && $this->hasTenancy();
    }
}
