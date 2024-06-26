<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Simple\Form;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Create extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can create a record via create modal action on the create page for simple resource: '.class_basename($this->resource);
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->isSimpleResource($this->resource) &&
            $this->getResourceCreateFormRequiredFields($this->resource)->isNotEmpty();
    }

    public function getVariables(): array
    {
        $requiredFields = $this->getResourceCreateFormRequiredFields($this->resource)->keys();

        $requiredFieldsWithNullString = '['.$requiredFields->map(function ($item) {
            return '"'.$item.'" => null';
        })->implode(', ').']';

        $requiredFieldsFromFactoryString = '['.$requiredFields->map(function ($item) {
            return '"'.$item.'" => $record->'.$item;
        })->implode(', ').']';

        return [
            'CREATE_PAGE_REQUIRED_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceCreateFormRequiredFields($this->resource)->keys()->implode('", "')),
            'CREATE_PAGE_OPTIONAL_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceCreateFormOptionalFields($this->resource)->keys()->implode('", "')),
            'REQUIRED_FIELDS_WITH_NULL' => $this->convertDoubleQuotedArrayString($requiredFieldsWithNullString),
            'REQUIRED_FIELDS_FROM_FACTORY' => $this->convertDoubleQuotedArrayString($requiredFieldsFromFactoryString),

        ];
    }
}
