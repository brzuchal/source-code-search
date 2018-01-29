<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\UnitTest\Application;

use Brzuchal\SourceCodeSearch\Application\QueryPage;

class QueryPageTest extends \PHPUnit\Framework\TestCase
{
    public function testNotEmpty(): QueryPage
    {
        $queryPage = QueryPage::createFromInts(1, 20);
        $this->assertNotEmpty($queryPage);
        $this->assertInstanceOf(QueryPage::class, $queryPage);

        return $queryPage;
    }

    /**
     * @depends testNotEmpty
     */
    public function testGetPageNumber(QueryPage $queryPage): void
    {
        $pageNumber = $queryPage->getPageNumber();
        $this->assertNotEmpty($pageNumber);
        $this->assertEquals(1, $pageNumber);
    }

    /**
     * @depends testNotEmpty
     */
    public function testGetPerPageLimit(QueryPage $queryPage): void
    {
        $perPageLimit = $queryPage->getPerPageLimit();
        $this->assertNotEmpty($perPageLimit);
        $this->assertEquals(20, $perPageLimit);
    }
}
