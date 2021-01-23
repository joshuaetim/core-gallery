<?php declare(strict_types=1);

namespace BlogCore;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HelloWorld
{
    public function announce(ServerRequestInterface $request): ResponseInterface
    {
        $response = new \Laminas\Diactoros\Response;
        $response->getBody()->write('<h2> From Hello World Class</h2>');
        return $response;
    }
}