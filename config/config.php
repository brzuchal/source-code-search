<?php declare(strict_types=1);

use Brzuchal\SourceCodeSearch\Infrastructure\GithubSearchService;

return [
    'sort_field' => 'BEST_MATCH',
    'sort_order' => 'DESC',
    'per_page_limit' => 20,
    'search_service' => GithubSearchService::class,
];
