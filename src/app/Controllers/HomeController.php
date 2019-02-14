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

use Slim\Http\Request;
use Slim\Http\Response;


class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'index.twig');
    }

    public function error(Request $request, Response $response)
    {
        return $this->container->get('customErrorHandler')->render($response, 404, 'Page not found');
    }
}
