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
        return $this->getResourceCreateForm()::class == Form::class;
    }
}
