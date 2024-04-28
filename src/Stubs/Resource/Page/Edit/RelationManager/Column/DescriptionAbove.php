<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Column;

use CodeWithDennis\FilamentTests\Stubs\Base;

class DescriptionAbove extends Base
{
    public function getDescription(): string
    {
        return 'has the correct descriptions  on the '.str($this->relationManager)->basename()->snake()->replace('_', ' ').' on the edit page';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getRelationManagerTableColumns($this->relationManager)->isNotEmpty()
            && $this->getRelationManagerDescriptionAboveColumns($this->relationManager)->isNotEmpty();
    }

    public function getVariables(): array
    {
        return [
            'RELATION_MANAGER_NAME' => str($this->relationManager)->basename(),
            'RELATION_MANAGER_CLASS' => $this->relationManager.'::class',
            'RELATION_MANAGER_RELATIONSHIP_MODEL' => $this->getRelationManagerRelationshipNameToModelClass($this->relationManager),
            'RELATION_MANAGER_RELATIONSHIP_NAME' => str($this->getRelationManager($this->relationManager)->getRelationshipName())->ucfirst(),
            'RELATION_MANAGER_TABLE_DESCRIPTIONS_ABOVE_COLUMNS' => $this->transformToPestDataset($this->getRelationManagerTableColumnDescriptionAbove($this->relationManager), ['column', 'description']),
        ];
    }
}
