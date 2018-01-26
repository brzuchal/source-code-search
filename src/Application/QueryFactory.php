<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use Exception;

final class QueryFactory
{
    /** @var QuerySortField */
    private $defaultSortField;
    /** @var QuerySortOrder */
    private $defaultSortOrder;

    public function __construct(string $defaultSortField, string $defaultSortOrder)
    {
        $this->defaultSortField = QuerySortField::createFromConstantName(\strtoupper($defaultSortField));
        $this->defaultSortOrder = QuerySortOrder::createFromConstantName(\strtoupper($defaultSortOrder));
    }

    public function createQuery(string $queryString, ?string $sortField = null, ?string $sortOrder = null) : Query
    {
        try {
            $queryString = QueryString::fromString($queryString);
            $sortField = $this->createSortField($sortField);
            $sortOrder = $this->createSortOrder($sortOrder);
        } catch (Exception $exception) {
            throw new \InvalidArgumentException(
                "Unable to create query with string: {$queryString}, " .
                "sorted with: {$sortField} and order by: {$sortOrder}",
                0,
                $exception
            );
        }

        return new Query($queryString, $sortField, $sortOrder);
    }

    /**
     * @throws \Exception
     */
    private function createSortField(?string $sortField): QuerySortField
    {
        if (null !== $sortField) {
            return QuerySortField::createFromConstantName(\strtoupper($sortField));
        }

        return $this->defaultSortField;
    }

    /**
     * @throws \Exception
     */
    private function createSortOrder(?string $sortOrder): QuerySortOrder
    {
        if (null !== $sortOrder) {
            return QuerySortOrder::createFromConstantName(\strtoupper($sortOrder));
        }

        return $this->defaultSortOrder;
    }
}
