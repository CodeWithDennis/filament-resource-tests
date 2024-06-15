<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Field;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Disabled extends Base
{
    public function getDescription(): string
    {
        return 'has a disabled field on edit form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            collect($this->getResourceEditFields($this->resource))
                ->filter(fn ($field) => $field->isDisabled())->count();
    }

    public function getVariables(): array
    {
        return [
            'EDIT_PAGE_DISABLED_FIELDS' => $this->convertDoubleQuotedArrayString(collect($this->getResourceEditFormFields($this->resource))->filter(fn ($field) => $field->isDisabled())->keys()),
        ];
    }
}
