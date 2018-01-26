<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Test\Application;

use Brzuchal\SourceCodeSearch\Application\Query;
use Brzuchal\SourceCodeSearch\Application\QueryFactory;
use Brzuchal\SourceCodeSearch\Application\QuerySortField;
use Brzuchal\SourceCodeSearch\Application\QuerySortOrder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class QueryFactoryTest extends TestCase
{
    /** @var QueryFactory */
    private $factory;

    public function setUp(): void
    {
        $this->factory = new QueryFactory('best_match', 'desc');
    }

    public function testCreateQuery(): void
    {
        $query = $this->factory->createQuery('cats stars:>10');
        $this->assertNotEmpty($query);
        $this->assertInstanceOf(Query::class, $query);
        $this->assertEquals('cats stars:>10', (string)$query->getQueryString());
        $this->assertEquals(QuerySortField::BEST_MATCH(), $query->getSortField());
        $this->assertEquals(QuerySortOrder::DESC(), $query->getSortOrder());
    }

    public function testCreateQueryFail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->createQuery('');
    }
}
