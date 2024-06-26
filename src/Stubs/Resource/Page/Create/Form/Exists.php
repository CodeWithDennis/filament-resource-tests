<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form;

use CodeWithDennis\FilamentTests\Stubs\Base;
use Filament\Forms\Form;

class Exists extends Base
{
    public function getDescription(): string
    {
        return 'has create form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getGroupToConfig() &&
            ! $this->isSimpleResource($this->resource) &&
//            $this->hasPage('create', $this->resource) &&
            $this->getResourceCreateForm($this->resource)::class == Form::class;
    }
}
