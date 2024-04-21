<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Hidden extends Base
{
    public function getDescription(): string
    {
        return 'cannot render header actions on the index page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index')
            && $this->hasAnyHiddenIndexHeaderAction($this->getIndexHeaderActions()['hidden']->toArray());
    }

    public function getVariables(): array
    {
        return [
            'INDEX_PAGE_HIDDEN_HEADER_ACTIONS' => $this->convertDoubleQuotedArrayString($this->getIndexHeaderActions()['hidden']->values()),
        ];
    }
}
