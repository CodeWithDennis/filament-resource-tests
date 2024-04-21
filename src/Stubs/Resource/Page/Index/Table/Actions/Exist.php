<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Exist extends Base
{
    public function getDescription(): string
    {
        return 'has table action';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasAnyTableAction($this->getTableActionNames()->toArray());
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_ACTIONS' => $this->getTableActionNames()->keys(),
        ];
    }
}
