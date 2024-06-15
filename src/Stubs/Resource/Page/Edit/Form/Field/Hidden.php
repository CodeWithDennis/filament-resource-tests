<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Field;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Hidden extends Base
{
    public function getDescription(): string
    {
        return 'has a hidden field on edit form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            collect($this->getResourceEditFields($this->resource))
                ->filter(fn ($field) => $field->isHidden())->count();
    }

    public function getVariables(): array
    {
        return [
            'EDIT_PAGE_HIDDEN_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceEditFormHiddenFields($this->resource)->keys()),
        ];
    }
}
