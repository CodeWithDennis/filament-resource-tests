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
            collect($this->getResourceCreateFields($this->resource))->count();
    }

    public function getVariables(): array
    {
        return [
            'CREATE_PAGE_FIELDS' => $this->convertDoubleQuotedArrayString(collect($this->getResourceCreateFormFields($this->resource))->keys()),
        ];
    }
}
