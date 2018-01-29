<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

final class Query
{
    /** @var QueryString */
    private $queryString;
    /** @var QuerySortField */
    private $sortField;
    /** @var QuerySortOrder */
    private $sortOrder;
    /** @var QueryPage */
    private $queryPage;

    public function __construct(
        QueryString $queryString,
        QuerySortField $sortField,
        QuerySortOrder $sortOrder,
        QueryPage $queryPage
    ) {
        $this->queryString = $queryString;
        $this->sortField = $sortField;
        $this->sortOrder = $sortOrder;
        $this->queryPage = $queryPage;
    }

    public function getQueryString(): QueryString
    {
        return $this->queryString;
    }

    public function getSortField(): QuerySortField
    {
        return $this->sortField;
    }

    public function getSortOrder(): QuerySortOrder
    {
        return $this->sortOrder;
    }

    public function getPage(): QueryPage
    {
        return $this->queryPage;
    }
}
