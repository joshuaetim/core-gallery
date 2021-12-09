<?php declare(strict_types=1);

namespace BlogCore\Controllers;

use BlogCore\Models\Photo;
use BlogCore\Handlers\Database;
use \Laminas\Diactoros\Response;
use BlogCore\Handlers\ViewHandler;
use Psr\Http\Message\ResponseInterface;
use BlogCore\Controllers\BaseController;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends BaseController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        new Database();

        $photos = Photo::all();

        return ViewHandler::twig('home.html', ['title' => 'Home Page', 'photos' => $photos]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        return new Response\TextResponse($_ENV["APP_ENV"]);
    }
}