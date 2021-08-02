<?php declare(strict_types=1);

namespace BlogCore\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PageCount implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        
        session_start();

        if(isset($_SESSION['viewCount']) && $_SERVER['REQUEST_URI'] != '/favicon.ico'){
            $_SESSION['viewCount'] = $_SESSION['viewCount'] + 1;
        }
        else{
            $_SESSION['viewCount'] = 1;
        }

        $response = $handler->handle($request);

        return $response;
    }
}