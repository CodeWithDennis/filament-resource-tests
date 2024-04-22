<?php

namespace CodeWithDennis\FilamentTests\Stubs\Resource\Page\Index;

use CodeWithDennis\FilamentTests\Stubs\Base;

class TenancyListRecordsPaginated extends Base
{
    public function getDescription(): string
    {
        return 'can list records on the index page with pagination (tenancy)';
    }

    public function getShouldGenerate(): bool
    {
        return $this->hasPage('index', $this->resource)
            && $this->tableHasPagination($this->resource)
            && $this->hasTenancy();
    }

    public function getVariables(): array
    {
        return [
            'DEFAULT_PER_PAGE_OPTION' => $this->convertDoubleQuotedArrayString($this->getTableDefaultPaginationPageOption($this->resource)),
            'DEFAULT_PAGINATED_RECORDS_FACTORY_COUNT' => $this->convertDoubleQuotedArrayString($this->getTableDefaultPaginationPageOption($this->resource) * 2),
        ];
    }
}
