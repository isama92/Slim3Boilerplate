<?php
/*
|--------------------------------------------------------------------------
| Old Input Middleware
|--------------------------------------------------------------------------
|
| Old input middleware, if there are any post values it keeps them in the session
|
*/

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;


class OldInputMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $session = $this->container->session;
        if($session->exists('old'))
            $this->container->view->getEnvironment()->addGlobal('old', $session->old);
        $session->set('old', $request->getParams());
        return $next($request, $response);
    }
}
