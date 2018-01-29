<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use UnexpectedValueException;

final class QueryBuilder
{
    /** @var QueryString */
    private $queryString;
    /** @var QuerySortField */
    private $querySortField;
    /** @var QuerySortOrder */
    private $querySortOrder;
    /** @var QueryPage */
    private $queryPage;

    public function __construct(
        string $defaultSortField,
        string $defaultSortOrder,
        int $pageNumber,
        int $perPageLimit
    ) {
        $this->querySortField = QuerySortField::createFromConstantName(\strtoupper($defaultSortField));
        $this->querySortOrder = QuerySortOrder::createFromConstantName(\strtoupper($defaultSortOrder));
        $this->queryPage = QueryPage::createFromInts($pageNumber, $perPageLimit);
    }

    public function withQuery(string $queryString): self
    {
        $builder = clone $this;
        $builder->queryString = QueryString::fromString($queryString);

        return $builder;
    }

    public function withSort(string $sortField, ?string $sortOrder = null): self
    {
        $builder = clone $this;
        $builder->querySortField = QuerySortField::createFromConstantName(\strtoupper($sortField));
        if (null !== $sortOrder) {
            $builder->querySortOrder = QuerySortOrder::createFromConstantName(\strtoupper($sortOrder));
        }

        return $builder;
    }

    public function withPage(int $pageNumber, ?int $perPageLimit)
    {
        $builder = clone $this;
        $builder->queryPage = QueryPage::createFromInts(
            $pageNumber,
            $perPageLimit ?? $this->queryPage->getPerPageLimit()
        );

        return $builder;
    }

    public function build(): Query
    {
        if (null === $this->queryString) {
            throw new UnexpectedValueException('Unable to build query without query string, use withQuery');
        }

        return new Query(
            $this->queryString,
            $this->querySortField,
            $this->querySortOrder,
            $this->queryPage
        );
    }
}
