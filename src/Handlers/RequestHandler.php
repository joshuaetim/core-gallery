<?php declare(strict_types=1);

namespace BlogCore\Handlers;

use Psr\Http\Message\ServerRequestInterface;

class RequestHandler
{
    /**
     * Get the last parameter from my pretty string
     * example, from edit/3 get 3
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public static function getLastParam(ServerRequestInterface $request)
    {
        $targetAddress = $request->getRequestTarget();
        $param = substr($targetAddress, strrpos($targetAddress, '/') + 1);

        return $param;
    }
}