<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Ui\Controller;

use Brzuchal\SourceCodeSearch\Application\QueryBuilder;
use Brzuchal\SourceCodeSearch\Application\SearchService;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SearchController
{
    /** @var SearchService */
    private $searchService;
    /** @var QueryBuilder */
    private $queryBuilder;
    /** @var string */
    private $sortField;
    /** @var string */
    private $sortOrder;
    /** @var int */
    private $perPageLimit;
    /** @var Serializer */
    private $serializer;

    public function __construct(
        SearchService $searchService,
        QueryBuilder $queryBuilder,
        string $sortField,
        string $sortOrder,
        int $perPageLimit,
        Serializer $serializer
    ) {
        $this->searchService = $searchService;
        $this->queryBuilder = $queryBuilder;
        $this->sortField = $sortField;
        $this->sortOrder = $sortOrder;
        $this->perPageLimit = $perPageLimit;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): Response
    {
        $queryString = $request->query->get('query');
        if (empty($queryString)) {
            throw new UnprocessableEntityHttpException(
                'Empty query parameter'
            );
        }
        $sortField = $request->query->get('sort');
        if (!empty($sortField) && false === \in_array($sortField, ['BEST_MATCH', 'INDEXED'], true)) {
            throw new UnprocessableEntityHttpException(
                "Invalid sort field parameter expecting (BEST_MATCH, INDEXED), given: {$sortField}"
            );
        }
        $sortOrder = $request->query->get('order');
        if (!empty($sortOrder) && false === \in_array($sortOrder, ['ASC', 'DESC'], true)) {
            throw new UnprocessableEntityHttpException(
                "Invalid sort order parameter expecting (ASC, DESC), given: {$sortOrder}"
            );
        }
        $pageNumber = $request->query->get('page') ?? 1;
        $perPageLimit = $request->query->get('limit');
        $query = $this->queryBuilder
            ->withQuery($queryString)
            ->withSort($sortField ?? $this->sortField, $sortOrder ?? $this->sortOrder)
            ->withPage($pageNumber ?? 1, $perPageLimit ?? $this->perPageLimit)
            ->build();
        $results = $this->searchService->find($query);

        return new JsonResponse($this->serializer->serialize($results, 'json'), 200, [], true);
    }
}
