<?php declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

define('BASEPATH', dirname(__DIR__));

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();

$router = new League\Route\Router;

$strategy = new League\Route\Strategy\JsonStrategy(new Laminas\Diactoros\ResponseFactory());

$router->get('/', [BlogCore\Controller\HomeController::class, 'index']);

$router->get('/add', [BlogCore\Controller\PhotoController::class, 'create']);

$router->post('/add', [BlogCore\Controller\PhotoController::class, 'store']);

$router->get('/sandbox', function(ServerRequestInterface $request): ResponseInterface{
    $package = new Symfony\Component\Asset\PathPackage(BASEPATH.'/views', new Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy);

    $response = $package->getUrl('layout.html');
    return new Laminas\Diactoros\Response\HtmlResponse(file_get_contents($response));
});

$response = $router->dispatch($request);

$emitter = new Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
$emitter->emit($response);