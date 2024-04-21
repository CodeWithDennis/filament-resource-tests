<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Fields;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Exists extends Base
{
    public function getDescription(): string
    {
        return 'has a field on edit form';
    }

    public function getShouldGenerate(): bool
    {
        return collect($this->getResourceEditFields())->count();
    }

    public function getVariables(): array
    {
        return [
            'EDIT_PAGE_FIELDS' => $this->convertDoubleQuotedArrayString(collect($this->getResourceEditFields())->keys()),
        ];
    }
}
