<?php
/*
|--------------------------------------------------------------------------
| Error helper
|--------------------------------------------------------------------------
|
| It renders an error screen
|
*/

namespace App\Helpers;

use Slim\Http\Response;


class Error
{
    private $container;
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function render(Response $response, $code, $msg)
    {
        return $this->container->view->render($response, 'error.twig', [
            'code' => $code,
            'msg' => $msg,
        ]);
    }
}
