<?php
/*
|--------------------------------------------------------------------------
| Csrf View Middleware
|--------------------------------------------------------------------------
|
| Csrf view middleware, register csrf fields in the view to add them later in templates
|
*/

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;


class CsrfViewMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $csrf = $this->container->get('csrf');
        $this->container->get('view')->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $csrf->getTokenNameKey() . '" value="' . $csrf->getTokenName() . '"/>
                <input type="hidden" name="' . $csrf->getTokenValueKey() . '" value="' . $csrf->getTokenValue() . '"/>
            ',
        ]);

        return $next($request, $response);
    }
}