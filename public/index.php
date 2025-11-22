<?php
require __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;
use App\Routes;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$routes = new Routes($app);
$routes->register();

$app->get('/', function ($req, $res) {
    $html = file_get_contents(__DIR__ . '/../templates/index.html');
    $res->getBody()->write($html);
    return $res;
});

$app->run();
