<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\UnitTest\Application;

use Brzuchal\SourceCodeSearch\Application\QueryString;
use PHPUnit\Framework\TestCase;

class QueryStringTest extends TestCase
{
    public function testNotEmpty(): QueryString
    {
        $queryString = QueryString::fromString('cats stars:>10');
        $this->assertNotEmpty($queryString);
        $this->assertInstanceOf(QueryString::class, $queryString);

        return $queryString;
    }

    /**
     * @depends testNotEmpty
     */
    public function testToString(QueryString $queryString): void
    {
        $this->assertNotEmpty($queryString->toString());
    }

    /**
     * @depends testNotEmpty
     */
    public function test__toString(QueryString $queryString): void
    {
        $this->assertNotEmpty($queryString->toString());
    }

    /**
     * @dataProvider validQueryStringProvider
     */
    public function testValidQueryString(string $queryString): void
    {
        $queryString = QueryString::fromString($queryString);
        $this->assertNotEmpty($queryString);
        $this->assertInstanceOf(QueryString::class, $queryString);
    }

    /**
     * @dataProvider invalidQueryStringProvider
     */
    public function testInvalidQueryString(string $queryString): void
    {
        $this->expectException(\InvalidArgumentException::class);
        QueryString::fromString($queryString);
    }

    public function validQueryStringProvider(): array
    {
        return [
            'find addClass in jQuery repo' => ['addClass in:file language:js repo:jquery/jquery'],
            'find DateTime object creation in all PHP repos' => ['new DateTime in:file language:php'],
            'find cats with more than 100 stars' => ['cats stars:>100'],
            'find cats with more than 1 star' => ['cats stars:1..*'],
        ];
    }

    public function invalidQueryStringProvider(): array
    {
        return [
            'fail on #' => ['cats #comment'],
            'fail on ?' => ['cats?'],
        ];
    }
}
