<?php declare(strict_types=1);

namespace BlogCore\Controller;

use BlogCore\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Laminas\Diactoros\Response;

class HomeController extends BaseController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $loader = new \Twig\Loader\FilesystemLoader(BASEPATH.'/views');
        $twig = new \Twig\Environment($loader);
        $content = $twig->render('home.html', ['title' => 'Home Page']);
        $response = new Response\HtmlResponse($content);
        return $response;
    }
}