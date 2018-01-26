<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch;
use Brzuchal\SourceCodeSearch\Application\QueryFactory;
use Brzuchal\SourceCodeSearch\Application\ResultBuilder;
use Brzuchal\SourceCodeSearch\Application\ResultItemFactory;
use Github\Client;

require __DIR__ . '/vendor/autoload.php';

$client = new Client();
$resultBuilder = new ResultBuilder(new ResultItemFactory());
$searchService = new Infrastructure\GithubSearchService($client, $resultBuilder);

$factory = new QueryFactory('best_match', 'desc');
$query = $factory->createQuery('composer.json user:brzuchal');

$result = $searchService->find($query);

dump($result);
