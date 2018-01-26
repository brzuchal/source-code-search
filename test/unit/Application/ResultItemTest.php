<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Test\Application;

use Brzuchal\SourceCodeSearch\Application\ResultItem;
use Brzuchal\SourceCodeSearch\Application\ResultItemRepository;
use PHPUnit\Framework\TestCase;

class ResultItemTest extends TestCase
{
    public function testCreateFromStrings(): ResultItem
    {
        $item = ResultItem::createFromStrings(
            'composer.json',
            'composer.json',
            'source-code-search',
            'brzuchal'
        );
        $this->assertNotEmpty($item);
        $this->assertInstanceOf(ResultItem::class, $item);

        return $item;
    }

    /**
     * @depends testCreateFromStrings
     */
    public function testGetName(ResultItem $item): void
    {
        $name = $item->getName();
        $this->assertNotEmpty($name);
        $this->assertEquals('composer.json', $name);
    }

    /**
     * @depends testCreateFromStrings
     */
    public function testGetPath(ResultItem $item): void
    {
        $path = $item->getPath();
        $this->assertNotEmpty($path);
        $this->assertEquals('composer.json', $path);
    }

    /**
     * @depends testCreateFromStrings
     */
    public function testGetRepository(ResultItem $item): void
    {
        $repository = $item->getRepository();
        $this->assertNotEmpty($repository);
        $this->assertInstanceOf(ResultItemRepository::class, $repository);
    }
}
