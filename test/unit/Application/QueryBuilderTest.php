<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\UnitTest\Application;

use Brzuchal\SourceCodeSearch\Application\Query;
use Brzuchal\SourceCodeSearch\Application\QueryBuilder;
use Brzuchal\SourceCodeSearch\Application\QueryPage;
use Brzuchal\SourceCodeSearch\Application\QuerySortField;
use Brzuchal\SourceCodeSearch\Application\QuerySortOrder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /** @var QueryBuilder */
    private $builder;

    public function setUp(): void
    {
        $this->builder = new QueryBuilder(
            'best_match',
            'desc',
            1,
            20
        );
    }

    public function testCreateQuery(): void
    {
        $query = $this->builder->withQuery('cats stars:>10')->build();
        $this->assertNotEmpty($query);
        $this->assertInstanceOf(Query::class, $query);
        $this->assertEquals('cats stars:>10', (string)$query->getQueryString());
        $this->assertEquals(QuerySortField::BEST_MATCH(), $query->getSortField());
        $this->assertEquals(QuerySortOrder::DESC(), $query->getSortOrder());
        $this->assertInstanceOf(QueryPage::class, $query->getPage());
    }

    public function testCreateQueryFail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->builder->withQuery('');
    }
}
