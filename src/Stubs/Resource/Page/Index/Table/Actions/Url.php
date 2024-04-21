<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Url extends Base
{
    public function getDescription(): string
    {
        return 'has the correct URL for table action';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasTableActionWithUrl();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_ACTIONS_WITH_URL' => $this->transformToPestDataset($this->getTableActionsWithUrlValues(), ['name', 'url']),
        ];
    }
}
