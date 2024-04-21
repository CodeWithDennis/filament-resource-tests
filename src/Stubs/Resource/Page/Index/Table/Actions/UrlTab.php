<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class UrlTab extends Base
{
    public function getDescription(): string
    {
        return 'has the correct URL and opens in a new tab for table action';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasTableActionWithUrlThatShouldOpenInNewTab();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_ACTIONS_WITH_URL_THAT_SHOULD_OPEN_IN_NEW_TAB' => $this->transformToPestDataset($this->getTableActionsWithUrlThatShouldOpenInNewTabValues(), ['name', 'url']),
        ];
    }
}
