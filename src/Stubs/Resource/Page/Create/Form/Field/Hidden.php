<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Hidden extends Base
{
    public function getDescription(): string
    {
        return 'has a hidden field on create form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->hasPage('create', $this->resource) &&
            $this->getResourceCreateFormHiddenFields($this->resource)->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'CREATE_PAGE_HIDDEN_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceCreateFormHiddenFields($this->resource)->keys()),
        ];
    }
}
