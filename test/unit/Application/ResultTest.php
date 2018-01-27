<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\UnitTest\Application;

use Brzuchal\SourceCodeSearch\Application\Result;
use Brzuchal\SourceCodeSearch\Application\ResultItem;
use Brzuchal\SourceCodeSearch\Application\ResultItemRepository;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testCreateResult(): Result
    {
        $items = [
            new ResultItem(
                'composer.json',
                'composer.json',
                ResultItemRepository::createFromStrings('source-code-search', 'brzuchal')
            ),
            new ResultItem(
                'composer.lock',
                'composer.lock',
                ResultItemRepository::createFromStrings('source-code-search', 'brzuchal')
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
