<?php
/*
|--------------------------------------------------------------------------
| Validation Error Middleware
|--------------------------------------------------------------------------
|
| Validation error middleware, if there are any error it passes them to the template
|
*/

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;


class ValidationErrorMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $session = $this->container->get('session');
        if($session->exists('errors')) {
            $this->container->get('view')->getEnvironment()->addGlobal('errors', $session->errors);
            $session->delete('errors');
        }
        return $next($request, $response);
    }
}
