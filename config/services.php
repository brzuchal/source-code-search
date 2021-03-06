<?php declare(strict_types=1);

use Brzuchal\SourceCodeSearch\Application\QueryBuilder;
use Brzuchal\SourceCodeSearch\Application\ResultBuilder;
use Brzuchal\SourceCodeSearch\Application\ResultItemFactory;
use Brzuchal\SourceCodeSearch\Application\SearchService;
use Brzuchal\SourceCodeSearch\Infrastructure\GithubSearchService;
use Brzuchal\SourceCodeSearch\Ui\Command\SearchCommand;
use Brzuchal\SourceCodeSearch\Ui\Controller\SearchController;
use Brzuchal\SourceCodeSearch\Ui\Controller\SwaggerController;
use Github\Client;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Pimple\Container;
use Silex\Application as SilexApplication;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

$config = (array)require __DIR__ . '/config.php';
$container = new Container($config);

$container[ResultItemFactory::class] = function (): ResultItemFactory {
    return new ResultItemFactory();
};
$container[ResultBuilder::class] = function (Container $container): ResultBuilder {
    return new ResultBuilder($container[ResultItemFactory::class]);
};
$container[QueryBuilder::class] = function (Container $container): QueryBuilder {
    return new QueryBuilder(
        $container['sort_field'],
        $container['sort_order'],
        1,
        $container['per_page_limit']
    );
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

$container[Serializer::class] = function (Container $container): Serializer {
    \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
        'JMS\Serializer\Annotation',
        \dirname(__DIR__).'/vendor/jms/serializer/src'
    );

    $serializeBuilder = SerializerBuilder::create();
    $serializeBuilder->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy());

    return $serializeBuilder->build();
};

// CLI

$container[SearchCommand::class] = function (Container $container): SearchCommand {
    return new SearchCommand(
        $container[SearchService::class],
        $container[QueryBuilder::class],
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
$container[ConsoleApplication::class] = function (Container $container): ConsoleApplication {
    $app = new ConsoleApplication('source-code-search');
    $app->addCommands($container['app.console.commands']);

    return $app;
};

// WEB

$container[SearchController::class] = function (Container $container): SearchController {
    return new SearchController(
        $container[SearchService::class],
        $container[QueryBuilder::class],
        $container['sort_field'],
        $container['sort_order'],
        $container['per_page_limit'],
        $container[Serializer::class]
    );
};
$container[SwaggerController::class] = function (): SwaggerController {
    return new SwaggerController();
};

$container[SilexApplication::class] = function (Container $container) : SilexApplication {
    $app = new SilexApplication();
    $app->get('/', function (Request $request): Response {
        return new Response(<<<HTML
<!DOCTYPE html>
<html>
<head><title>Source Code Search</title></head>
<body>
    <h1>Source Code Search</h1>
    <p>Read <a href="/swagger.json">Swagger Documentation</a> or 
    navigate to API <a href="/api/search">endpoint</a></p>
</body>
</html>
HTML
);
    });
    $app->get('/swagger.json', $container[SwaggerController::class]);
    $app->get('/api/search', $container[SearchController::class]);
    $app->error(function (\Exception $e, $code) use($app) {
        if ($e instanceof HttpException) {
            return $app->json([
                'error' => $e->getMessage()
            ],$e->getStatusCode());
        }
        return $app->json([
            'error' => $e->getMessage()
        ], 500);
    });

    return $app;
};
return $container;
