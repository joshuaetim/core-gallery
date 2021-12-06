<?php declare(strict_types=1);

namespace BlogCore\Handlers;

use Laminas\Diactoros\Response;

class ViewHandler
{
    /**
     * Return view from Twig Templating Engine
     * Views are stored in BASEPATH.'/views'
     * @param string $location
     * @param array $viewData
     */
    public static function twig($location, $viewData)
    {
        $loader = new \Twig\Loader\FilesystemLoader(BASEPATH.'/views');
        $twig = new \Twig\Environment($loader);
        $content = $twig->render($location, $viewData);
        $response = new Response\HtmlResponse($content);
        return $response;
    }
}

