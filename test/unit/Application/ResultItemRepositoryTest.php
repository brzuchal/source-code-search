<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Test\Application;

use Brzuchal\SourceCodeSearch\Application\ResultItemRepository;
use PHPUnit\Framework\TestCase;

class ResultItemRepositoryTest extends TestCase
{
    public function testCreateFromStrings(): ResultItemRepository
    {
        $repository = ResultItemRepository::createFromStrings('source-code-search', 'brzuchal');
        $this->assertNotEmpty($repository);
        $this->assertInstanceOf(ResultItemRepository::class, $repository);

        return $repository;
    }

    /**
     * @depends testCreateFromStrings
     */
    public function testGetName(ResultItemRepository $repository): void
    {
        $name = $repository->getName();
        $this->assertNotEmpty($name);
        $this->assertEquals('source-code-search', $name);
    }

    /**
     * @depends testCreateFromStrings
     */
    public function testGetOwner(ResultItemRepository $repository): void
    {
        $owner = $repository->getOwner();
        $this->assertNotEmpty($owner);
        $this->assertEquals('brzuchal', $owner);
    }
}
