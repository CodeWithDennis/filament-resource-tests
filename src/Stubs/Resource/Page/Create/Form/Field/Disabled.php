<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Disabled extends Base
{
    public function getDescription(): string
    {
        return 'has a disabled field on create form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->hasPage('create', $this->resource) &&
            $this->getResourceCreateFormDisabledFields($this->resource)->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'CREATE_PAGE_DISABLED_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceCreateFormDisabledFields($this->resource)->keys()),
        ];
    }
}
