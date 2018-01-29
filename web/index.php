<?php declare(strict_types=1);

use Pimple\Container;
use Silex\Application;

require __DIR__ . '/../vendor/autoload.php';
/** @var Container $container */
$container = require __DIR__ . '/../config/services.php';



/** @var Application $app */
$app = $container[Application::class];
//$app['debug'] = true;
$app->run();
