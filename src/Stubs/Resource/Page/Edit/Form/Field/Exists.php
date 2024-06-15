<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Field;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Exists extends Base
{
    public function getDescription(): string
    {
        return 'has a field on edit form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->hasPage('edit', $this->resource) &&
        collect($this->getResourceEditFields($this->resource))->count();
    }

    public function getVariables(): array
    {
        return [
            'EDIT_PAGE_FIELDS' => $this->convertDoubleQuotedArrayString(collect($this->getResourceEditFormFields($this->resource))->keys()),
        ];
    }
}
