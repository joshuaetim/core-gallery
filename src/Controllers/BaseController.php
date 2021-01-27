<?php declare(strict_types=1);

namespace BlogCore\Controllers;

use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;

/**
 * This Base Controller covers logic that will be common to all or most controllers.
*/
class BaseController
{
    public function sendErrorResponse(string $message, $error, int $code = 400): ResponseInterface
    {
        $data = [
            'message' => $message,
            'error' => $error,
        ];

        $response = new Response\JsonResponse($data);
        $response->withStatus($code);

        return $response;
    }

    public function sendResponse(string $message, $data, int $code = 200): ResponseInterface
    {
        $data = [
            'message' => $message,
            'data' => $data,
        ];

        $response = new Response\JsonResponse($data);
        $response->withStatus($code);

        return $response;
    }
}