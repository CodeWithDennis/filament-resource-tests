<?php

namespace CodeWithDennis\FilamentTests\Handlers;

use Filament\Resources\Resource;
use Illuminate\Support\Collection;

class StubHandler
{
    public function __construct(public Resource $resource)
    {
    }

    public function setResource(Resource $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getStubs(): Collection
    {
        $resource = $this->resource;

        $stubs = [
            \CodeWithDennis\FilamentTests\Stubs\SetupStub::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\TenancySetupStub::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Pages\Auth\Registration\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Pages\Auth\PasswordReset\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Pages\Auth\Login\Render::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\ListRecords::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\TenancyListRecords::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\ListRecordsPaginated::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\TenancyListRecordsPaginated::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Trashed::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Actions\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Actions\Hidden::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Actions\Visible::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Heading::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Description::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\Url::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\UrlTab::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\Delete::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\DeleteSoft::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\Replicate::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\Restore::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Actions\DeleteForce::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkActions\Restore::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkActions\Delete::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkActions\DeleteForce::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkActions\DeleteSoft::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkActions\Exist::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\CannotRender::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\Sort::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\Search::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\SearchIndividually::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\DescriptionAbove::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\DescriptionBelow::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\Select::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Columns\ExtraAttributes::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries\Average::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries\Count::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries\CountIcon::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries\Range::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries\DateRange::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summaries\Sum::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filters\Reset::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filters\Add::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filters\Remove::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Fields\Disabled::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Fields\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Fields\Hidden::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Fields\Validate::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Validate::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Fields\Disabled::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Fields\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Fields\Hidden::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Fields\Validate::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Validate::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\Render::make($resource)->get(),
        ];

        return collect($stubs);
    }
}
