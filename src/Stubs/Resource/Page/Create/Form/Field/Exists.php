<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Exists extends Base
{
    public function getDescription(): string
    {
        return 'has a field on create form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->hasPage('create', $this->resource) &&
            $this->getResourceCreateFormFields($this->resource)->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'CREATE_PAGE_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceCreateFormFields($this->resource)->keys()),
        ];
    }
}
