<?php declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Http\Exception\NotFoundException;

define('BASEPATH', dirname(__DIR__));

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();

$router = new League\Route\Router;

$strategy = new League\Route\Strategy\JsonStrategy(new Laminas\Diactoros\ResponseFactory());

$router->get('/', [BlogCore\Controllers\HomeController::class, 'index'])
        ->middleware(new BlogCore\Middleware\PageCount);

$router->get('/add', [BlogCore\Controllers\PhotoController::class, 'create'])
        ->middleware(new BlogCore\Middleware\PageCount);

$router->get('/count/abc123', function(ServerRequestInterface $request): ResponseInterface{
    session_start();
    $_SESSION['before'] = $_SERVER['REQUEST_URI'];
    // session_unset();
    return new Response\TextResponse(strval($_SESSION['viewCount']));
});

$router->get('/unset', function(ServerRequestInterface $request): ResponseInterface{
    session_start();
    session_destroy();
    return new Response\TextResponse('session unset');
});

$router->get('/edit/{id}', [BlogCore\Controllers\PhotoController::class, 'edit'])
        ->middleware(new BlogCore\Middleware\PageCount);

$router->post('/add', [BlogCore\Controllers\PhotoController::class, 'store']);

$router->post('/update/{id}', [BlogCore\Controllers\PhotoController::class, 'update']);

$router->post('/delete/{id}', [BlogCore\Controllers\PhotoController::class, 'delete']);

try {
    $response = $router->dispatch($request);

    $emitter = new Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
    $emitter->emit($response);
} catch (NotFoundException $exception) {
    header('Location: /');
}
