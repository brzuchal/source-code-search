<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\IntegrationTest\Infrastructure;

use Brzuchal\SourceCodeSearch\Application\Query;
use Brzuchal\SourceCodeSearch\Application\QuerySortField;
use Brzuchal\SourceCodeSearch\Application\QuerySortOrder;
use Brzuchal\SourceCodeSearch\Application\QueryString;
use Brzuchal\SourceCodeSearch\Application\Result;
use Brzuchal\SourceCodeSearch\Application\ResultBuilder;
use Brzuchal\SourceCodeSearch\Application\ResultItemFactory;
use Brzuchal\SourceCodeSearch\Infrastructure\GithubSearchService;
use Github\Client;

class GithubSearchServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @var GithubSearchService */
    public $githubSearchService;

    public function setUp()
    {
        $this->githubSearchService = new GithubSearchService(
            new Client(),
            new ResultBuilder(new ResultItemFactory())
        );
    }

    public function testFind()
    {
        $query = new Query(
            QueryString::fromString('composer.json user:brzuchal'),
            QuerySortField::BEST_MATCH(),
            QuerySortOrder::DESC()
        );
        $result = $this->githubSearchService->find($query);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Result::class, $result);
    }
}
