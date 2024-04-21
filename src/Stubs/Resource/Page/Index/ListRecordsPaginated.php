<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index;

use CodeWithDennis\FilamentTests\Stubs\Base;

class ListRecordsPaginated extends Base
{
    public function getDescription(): string
    {
        return 'can list records on the index page with pagination';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index') && $this->tableHasPagination();
    }

    public function getVariables(): array
    {
        return [
            'DEFAULT_PER_PAGE_OPTION' => $this->convertDoubleQuotedArrayString($this->getTableDefaultPaginationPageOption()),
            'DEFAULT_PAGINATED_RECORDS_FACTORY_COUNT' => $this->convertDoubleQuotedArrayString($this->getTableDefaultPaginationPageOption() * 2),
        ];
    }
}
