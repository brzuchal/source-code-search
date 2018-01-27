<?php declare(strict_types=1);

use Brzuchal\SourceCodeSearch\Application\QueryFactory;
use Brzuchal\SourceCodeSearch\Application\ResultBuilder;
use Brzuchal\SourceCodeSearch\Application\ResultItemFactory;
use Brzuchal\SourceCodeSearch\Application\SearchService;
use Brzuchal\SourceCodeSearch\Infrastructure\GithubSearchService;
use Brzuchal\SourceCodeSearch\Ui\Command\SearchCommand;
use Github\Client;
use Pimple\Container;
use Symfony\Component\Console\Application;

$config = (array)require __DIR__ . '/config.php';
$container = new Container($config);

$container[ResultItemFactory::class] = function (): ResultItemFactory {
    return new ResultItemFactory();
};
$container[ResultBuilder::class] = function (Container $container): ResultBuilder {
    return new ResultBuilder($container[ResultItemFactory::class]);
};
$container[QueryFactory::class] = function (Container $container): QueryFactory {
    return new QueryFactory($container['sort_field'], $container['sort_order']);
};

$container[Client::class] = function (): Client {
    return new Client();
};
$container[GithubSearchService::class] = function (Container $container): GithubSearchService {
    return new GithubSearchService($container[Client::class], $container[ResultBuilder::class]);
};

$container[SearchService::class] = function (Container $container): SearchService {
    return $container[$container['search_service']];
};

$container[SearchCommand::class] = function (Container $container): SearchCommand {
    return new SearchCommand(
        $container[SearchService::class],
        $container[QueryFactory::class],
        $container['sort_field'],
        $container['sort_order'],
        $container['per_page_limit']
    );
};
$container['app.console.commands'] = function (Container $container): array {
    return [
        $container[SearchCommand::class],
    ];
};
$container[Application::class] = function (Container $container): Application {
    $app = new Application('source-code-search');
    $app->addCommands($container['app.console.commands']);

    return $app;
};

return $container;
