<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Test\Application;

use Brzuchal\SourceCodeSearch\Application\Result;
use Brzuchal\SourceCodeSearch\Application\ResultItem;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testCreateResult(): Result
    {
        $items = [
            ResultItem::createFromStrings(
                'composer.json',
                'composer.json',
                'source-code-search',
                'brzuchal'
            ),
            ResultItem::createFromStrings(
                'composer.lock',
                'composer.lock',
                'source-code-search',
                'brzuchal'
            ),
        ];
        $result = new Result($items, \count($items), 1, 1);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Result::class, $result);

        return $result;
    }

    /**
     * @depends testCreateResult
     */
    public function testGetTotalCount(Result $result): void
    {
        $totalCount = $result->getTotalCount();
        $this->assertNotEmpty($totalCount);
        $this->assertEquals(2, $totalCount);
    }

    /**
     * @depends testCreateResult
     */
    public function testGetPageNumber(Result $result): void
    {
        $pageNumber = $result->getPageNumber();
        $this->assertNotEmpty($pageNumber);
        $this->assertEquals(1, $pageNumber);
    }

    /**
     * @depends testCreateResult
     */
    public function testNumberOfPages(Result $result): void
    {
        $numberOfPages = $result->getNumberOfPages();
        $this->assertNotEmpty($numberOfPages);
        $this->assertEquals(1, $numberOfPages);
    }

    /**
     * @depends testCreateResult
     */
    public function testTraversable(Result $result): void
    {
        $this->assertContainsOnlyInstancesOf(ResultItem::class, $result);
    }
}
