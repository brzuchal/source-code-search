<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\UnitTest\Application;

use Brzuchal\SourceCodeSearch\Application\ResultItem;
use Brzuchal\SourceCodeSearch\Application\ResultItemRepository;
use PHPUnit\Framework\TestCase;

class ResultItemTest extends TestCase
{
    public function testNotEmpty(): ResultItem
    {
        $item = new ResultItem(
            'composer.json',
            'composer.json',
            ResultItemRepository::createFromStrings('source-code-search', 'brzuchal')
        );
        $this->assertNotEmpty($item);
        $this->assertInstanceOf(ResultItem::class, $item);

        return $item;
    }

    /**
     * @depends testNotEmpty
     */
    public function testGetName(ResultItem $item): void
    {
        $name = $item->getName();
        $this->assertNotEmpty($name);
        $this->assertEquals('composer.json', $name);
    }

    /**
     * @depends testNotEmpty
     */
    public function testGetPath(ResultItem $item): void
    {
        $path = $item->getPath();
        $this->assertNotEmpty($path);
        $this->assertEquals('composer.json', $path);
    }

    /**
     * @depends testNotEmpty
     */
    public function testGetRepository(ResultItem $item): void
    {
        $repository = $item->getRepository();
        $this->assertNotEmpty($repository);
        $this->assertInstanceOf(ResultItemRepository::class, $repository);
    }
}
