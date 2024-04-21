<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Visible extends Base
{
    public function getDescription(): string
    {
        return 'can render header actions on the index page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index')
            && $this->hasAnyVisibleIndexHeaderAction($this->getIndexHeaderActions()['visible']->toArray());
    }

    public function getVariables(): array
    {
        return [
            'INDEX_PAGE_VISIBLE_HEADER_ACTIONS' => $this->convertDoubleQuotedArrayString($this->getIndexHeaderActions()['visible']->values()),
        ];
    }
}
