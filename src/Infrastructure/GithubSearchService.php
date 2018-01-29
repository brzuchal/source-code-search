<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Infrastructure;

use Brzuchal\SourceCodeSearch\Application\Query;
use Brzuchal\SourceCodeSearch\Application\QuerySortField;
use Brzuchal\SourceCodeSearch\Application\QuerySortOrder;
use Brzuchal\SourceCodeSearch\Application\Result;
use Brzuchal\SourceCodeSearch\Application\ResultBuilder;
use Brzuchal\SourceCodeSearch\Application\SearchService;
use Github\Client;

class GithubSearchService implements SearchService
{
    /** @var Client */
    private $client;
    /** @var ResultBuilder */
    private $resultBuilder;

    public function __construct(Client $client, ResultBuilder $resultBuilder)
    {
        $this->client = $client;
        $this->resultBuilder = $resultBuilder;
    }

    public function find(Query $query): Result
    {
        $sortField = $this->getSortFieldFromQuery($query);
        $sortOrder = $this->getSortOrderFromQuery($query);
        $page = $query->getPage();

        $searchApi = $this->client->search();
        $searchApi->setPage($page->getPageNumber());
        $searchApi->setPerPage($page->getPerPageLimit());
        /** @var array[] $result */
        $result = $searchApi->code((string)$query->getQueryString(), $sortField, $sortOrder);

        $resultBuilder = $this->resultBuilder
            ->withPageNumber((int)$searchApi->getPage())
            ->withPerPageLimit((int)$searchApi->getPerPage())
            ->withTotalCount((int)$result['total_count']);

        /** @var array $item */
        foreach ($result['items'] as $item) {
            $resultBuilder = $resultBuilder->withItem(
                $item['name'],
                $item['path'],
                $item['repository']['name'],
                $item['repository']['owner']['login']
            );
        }

        return $resultBuilder->build();
    }

    private function getSortFieldFromQuery(Query $query): string
    {
        switch (true) {
            case QuerySortField::INDEXED()->isEqual($query->getSortField()):
                $sortField = 'indexed';
                break;
            case QuerySortField::BEST_MATCH()->isEqual($query->getSortField()):
            default:
                $sortField = 'updated';
                break;
        }

        return $sortField;
    }

    private function getSortOrderFromQuery(Query $query): string
    {
        switch (true) {
            case QuerySortOrder::ASC()->isEqual($query->getSortOrder()):
                $sortOrder = 'asc';
                break;
            case QuerySortOrder::DESC()->isEqual($query->getSortOrder()):
            default:
                $sortOrder = 'desc';
                break;
        }

        return $sortOrder;
    }
}
