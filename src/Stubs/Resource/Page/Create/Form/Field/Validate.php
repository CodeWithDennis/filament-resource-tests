<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field;

use Closure;
use CodeWithDennis\FilamentTests\Stubs\Base;

class Validate extends Base
{
    public Closure|bool $isTodo = true;

    public function getDescription(): string
    {
        return 'can validate input on create form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            $this->hasPage('create', $this->resource) &&
            $this->getResourceCreateFormFields($this->resource)->isNotEmpty();
    }

    public function getVariables(): array
    {
        $requiredFields = $this->getResourceCreateFormRequiredFields($this->resource)->keys();
        $requiredFieldsWithRelationship = $this->getResourceCreateFormRequiredFieldWithRelationships($this->resource)->keys();

        $requiredFieldsWithNullString = '['.$requiredFields->map(function ($item) {
            return '"'.$item.'" => null';
        })->implode(', ').']';

        $requiredFieldsWithRequiredString = '['.$requiredFields->map(function ($item) {
            return '"'.$item.'" => "required"';
        })->implode(', ').']';

        $requiredFieldsFromFactoryString = '['.$requiredFields->map(function ($item) {
            return '"'.$item.'" => $record->'.$item;
        })->implode(', ').']';

        $relatedModelFactories = $requiredFieldsWithRelationship->map(function ($item) {
            $relationshipName = $this->getRelationNameFromAttribute($this->resource->getModel(), $item);
            $modelClass = (new \ReflectionClass($this->resource->getModel()))->getNamespaceName().'\\'.ucfirst($relationshipName);

            return '$'.$relationshipName.' = '.$modelClass.'::factory()->create();';
        })->implode("\n");

        $relationshipsAsFor = $requiredFieldsWithRelationship->map(function ($item) {
            $relationshipName = $this->getRelationNameFromAttribute($this->resource->getModel(), $item);

            return '->for($'.$relationshipName.')';
        })->implode("\n");

        return [
            'CREATE_PAGE_REQUIRED_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceCreateFormRequiredFields($this->resource)->keys()->implode('", "')),
            'CREATE_PAGE_OPTIONAL_FIELDS' => $this->convertDoubleQuotedArrayString($this->getResourceCreateFormOptionalFields($this->resource)->keys()->implode('", "')),
            'REQUIRED_FIELDS_WITH_NULL' => $this->convertDoubleQuotedArrayString($requiredFieldsWithNullString),
            'REQUIRED_FIELDS_WITH_REQUIRED' => $this->convertDoubleQuotedArrayString($requiredFieldsWithRequiredString),
            'REQUIRED_FIELDS_FROM_FACTORY' => $this->convertDoubleQuotedArrayString($requiredFieldsFromFactoryString),
            'RELATED_MODEL_FACTORIES' => $relatedModelFactories,
            'RELATIONSHIPS_AS_FOR' => $relationshipsAsFor,
        ];
    }
}
