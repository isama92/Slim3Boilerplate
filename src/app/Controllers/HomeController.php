<?php
/*
|--------------------------------------------------------------------------
| Home Controller
|--------------------------------------------------------------------------
|
| Home controller
|
*/

namespace App\Controllers;

use App\Helpers\Mailer;
use Slim\Http\Request;
use Slim\Http\Response;


class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'index.twig');
    }

    public function error404(Request $request, Response $response)
    {
        return $this->errorH->render($response, 404, 'Page not found');
    }
}
