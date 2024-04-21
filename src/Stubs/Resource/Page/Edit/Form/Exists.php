<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form;

use CodeWithDennis\FilamentTests\Stubs\Base;
use Filament\Forms\Form;

class Exists extends Base
{
    public function getDescription(): string
    {
        return 'has edit form';
    }

    public function getShouldGenerate(): bool
    {
        return $this->getResourceEditForm()::class == Form::class;
    }
}
