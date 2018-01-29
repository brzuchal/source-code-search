<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Infrastructure;

use Brzuchal\SourceCodeSearch\Application\Query;
use Brzuchal\SourceCodeSearch\Application\QuerySortField;
use Brzuchal\SourceCodeSearch\Application\QuerySortOrder;
use Brzuchal\SourceCodeSearch\Application\Result;
use Brzuchal\SourceCodeSearch\Application\ResultBuilder;
use Brzuchal\SourceCodeSearch\Application\SearchService;
use Github\Api\Search;
use Github\Client;
use Github\HttpClient\Message\ResponseMediator;

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

        $searchApi = $this->fixSearchApi($this->client->search());
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

    /**
     * We need to fix SearchApi because GitHub v3 accepts 'page' param instead of 'queryPage'
     */
    private function fixSearchApi(Search $searchApi): Search
    {
        return new class($searchApi) extends Search
        {
            /** @var \Github\Api\Search */
            private $search;

            public function __construct(Search $search)
            {
                $this->search = $search;
                parent::__construct($search->client);
            }

            protected function get($path, array $parameters = [], array $requestHeaders = [])
            {
                if (null !== $this->getPage() && !isset($parameters['page'])) {
                    $parameters['page'] = $this->getPage();
                }
                if (null !== $this->perPage && !isset($parameters['per_page'])) {
                    $parameters['per_page'] = $this->perPage;
                }
                if (\array_key_exists('ref', $parameters) && \is_null($parameters['ref'])) {
                    unset($parameters['ref']);
                }

                if (\count($parameters) > 0) {
                    $path .= '?' . \http_build_query($parameters);
                }

                $response = $this->client->getHttpClient()->get($path, $requestHeaders);

                return ResponseMediator::getContent($response);
            }
        };
    }
}
