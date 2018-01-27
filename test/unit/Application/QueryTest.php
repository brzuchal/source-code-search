<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\UnitTest\Application;

use Brzuchal\SourceCodeSearch\Application\Query;
use Brzuchal\SourceCodeSearch\Application\QuerySortField;
use Brzuchal\SourceCodeSearch\Application\QuerySortOrder;
use Brzuchal\SourceCodeSearch\Application\QueryString;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function testCreate(): Query
    {
        $query = new Query(
            QueryString::fromString('cats stars:>10'),
            QuerySortField::BEST_MATCH(),
            QuerySortOrder::DESC()
        );
        $this->assertNotEmpty($query);
        $this->assertInstanceOf(Query::class, $query);

        return $query;
    }

    /**
     * @depends testCreate
     */
    public function testGetQueryString(Query $query): void
    {
        $queryString = $query->getQueryString();
        $this->assertNotEmpty($queryString);
        $this->assertInstanceOf(QueryString::class, $queryString);
    }

    /**
     * @depends testCreate
     */
    public function testGetSortField(Query $query): void
    {
        $sortField = $query->getSortField();
        $this->assertNotEmpty($sortField);
        $this->assertInstanceOf(QuerySortField::class, $sortField);
    }

    /**
     * @depends testCreate
     */
    public function testGetSortOrder(Query $query): void
    {
        $sortOrder = $query->getSortOrder();
        $this->assertNotEmpty($sortOrder);
        $this->assertInstanceOf(QuerySortOrder::class, $sortOrder);
    }
}
