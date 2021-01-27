<?php declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

define('BASEPATH', dirname(__DIR__));

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();

$router = new League\Route\Router;

$strategy = new League\Route\Strategy\JsonStrategy(new Laminas\Diactoros\ResponseFactory());

$router->get('/', [BlogCore\Controllers\HomeController::class, 'index']);

$router->get('/add', [BlogCore\Controllers\PhotoController::class, 'create']);

$router->get('/edit/{id}', [BlogCore\Controllers\PhotoController::class, 'edit']);

$router->post('/add', [BlogCore\Controllers\PhotoController::class, 'store']);

$router->post('/update/{id}', [BlogCore\Controllers\PhotoController::class, 'update']);

$router->post('/delete/{id}', [BlogCore\Controllers\PhotoController::class, 'delete']);


$response = $router->dispatch($request);

$emitter = new Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
$emitter->emit($response);