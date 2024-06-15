<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Tabs extends Base
{
    public function getDescription(): string
    {
        return 'has tabs in order';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getIndexTabs($this->resource)->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'INDEX_TABS_AS_STRING_LIST' => $this->convertDoubleQuotedArrayString($this->getIndexTabsAsCommaSeparatedString($this->resource)),
        ];
    }
}
