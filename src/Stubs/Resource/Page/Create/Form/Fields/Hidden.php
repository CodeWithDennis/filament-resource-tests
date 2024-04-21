<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Fields;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Hidden extends Base
{
    public function getDescription(): string
    {
        return 'has a hidden field on create form';
    }

    public function getShouldGenerate(): bool
    {
        return collect($this->getResourceCreateFields())
            ->filter(fn ($field) => $field->isHidden())->count();
    }

    public function getVariables(): array
    {
        return [
            'CREATE_PAGE_HIDDEN_FIELDS' => $this->convertDoubleQuotedArrayString(collect($this->getResourceCreateFields())->filter(fn ($field) => $field->isHidden())->keys()),
        ];
    }
}
