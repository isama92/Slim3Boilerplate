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


class CustomErrorHandler
{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function render(Response $response, $code, $msg)
    {
        return $this->view->render($response, 'error.twig', [
            'code' => $code,
            'msg' => $msg,
        ]);
    }
}
