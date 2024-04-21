<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Fields;

use CodeWithDennis\FilamentTests\Stubs\Base;

class Exists extends Base
{
    public function getDescription(): string
    {
        return 'has a field on create form';
    }

    public function getShouldGenerate(): bool
    {
        return collect($this->getResourceCreateFields())->count();
    }

    public function getVariables(): array
    {
        return [
            'CREATE_PAGE_FIELDS' => $this->convertDoubleQuotedArrayString(collect($this->getResourceCreateFields())->keys()),
        ];
    }
}
