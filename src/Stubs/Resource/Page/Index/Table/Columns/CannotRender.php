<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns;

use CodeWithDennis\FilamentTests\Stubs\Base;

class CannotRender extends Base
{
    public function getDescription(): string
    {
        return 'cannot render column';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getToggledHiddenByDefaultColumns()->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RESOURCE_TABLE_TOGGLED_HIDDEN_BY_DEFAULT_COLUMNS' => $this->convertDoubleQuotedArrayString($this->getToggledHiddenByDefaultColumns()->keys()),
        ];
    }
}
