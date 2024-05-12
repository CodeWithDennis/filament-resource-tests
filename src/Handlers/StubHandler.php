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

        $resourceRelationManagers = collect($resource->getRelations());

        $stubs = [
            \CodeWithDennis\FilamentTests\Stubs\SetupStub::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Page\Auth\Registration\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Page\Auth\Registration\Register::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Page\Auth\PasswordReset\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Page\Auth\PasswordReset\Reset::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Page\Auth\Login\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Page\Auth\Login\Login::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Page\Auth\Logout\Logout::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\ListRecords::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\ListRecordsPaginated::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Trashed::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Action\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Action\Hidden::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Action\Visible::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Heading::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Description::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\Url::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\UrlTab::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\Delete::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\DeleteSoft::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\Replicate::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\Restore::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Action\DeleteForce::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkAction\Restore::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkAction\Delete::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkAction\DeleteForce::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkAction\DeleteSoft::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\BulkAction\Exist::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\CannotRender::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\Sort::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\Search::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\SearchIndividually::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\DescriptionAbove::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\DescriptionBelow::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\Select::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Column\ExtraAttributes::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summary\Average::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summary\Count::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summary\CountIcon::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summary\Range::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summary\DateRange::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Summary\Sum::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filter\Reset::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filter\Add::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index\Table\Filter\Remove::make($resource)->get(),

            //            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\ListRecords::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\ListRecordsPaginated::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Trashed::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Action\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Action\Hidden::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Action\Visible::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Heading::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Description::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\Url::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\UrlTab::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\Delete::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\DeleteSoft::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\Replicate::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\Restore::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Action\DeleteForce::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\BulkAction\Restore::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\BulkAction\Delete::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\BulkAction\DeleteForce::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\BulkAction\DeleteSoft::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\BulkAction\Exist::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\Exist::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\CannotRender::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\State::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\Sort::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\Search::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\SearchIndividually::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\DescriptionAbove::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\DescriptionBelow::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\Select::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Column\ExtraAttributes::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Summary\Average::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Summary\Count::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Summary\CountIcon::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Summary\Range::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Summary\DateRange::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Summary\Sum::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Filter\Reset::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Filter\Add::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\RelationManager\Table\Filter\Remove::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Render::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Action\Render::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Widget\Render::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field\Disabled::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field\Hidden::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Field\Validate::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Create\Form\Validate::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Action\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Widget\Render::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Field\Disabled::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Field\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Field\Hidden::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Field\Validate::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Exists::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Validate::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\Form\Fill::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\Render::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\Action\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\Form\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\RelationManager\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\View\Widget\Render::make($resource)->get(),

            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Custom\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Custom\Action\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Custom\Form\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Custom\Infolist\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Custom\RelationManager\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Custom\Table\Render::make($resource)->get(),
            \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Custom\Widget\Render::make($resource)->get(),
        ];

        $relationManagerStubs = $resourceRelationManagers->map(function ($relation) use ($resource) {
            if (! is_string($relation)) {
                $relation = $relation->relationManager;
            }

            return [
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Render::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\ListRecords::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\ListRecordsPaginated::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Heading::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Description::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\Render::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\CannotRender::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\DescriptionAbove::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\DescriptionBelow::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\Exist::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\ExtraAttributes::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\Search::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\SearchIndividually::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\Select::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Column\Sort::make($resource, $relation)->get(),

                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Filter\Add::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Filter\Remove::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Filter\Reset::make($resource, $relation)->get(),

                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Summary\Average::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Summary\Count::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Summary\CountIcon::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Summary\DateRange::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Summary\Range::make($resource, $relation)->get(),
                \CodeWithDennis\FilamentTests\Stubs\Resource\Page\Edit\RelationManager\Table\Summary\Sum::make($resource, $relation)->get(),
            ];
        })->toArray();

        $stubs = array_merge($stubs, ...$relationManagerStubs);

        return collect($stubs);
    }
}
