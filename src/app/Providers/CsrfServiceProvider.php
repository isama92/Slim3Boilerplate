<?php
/*
|--------------------------------------------------------------------------
| CsrfServiceProvider
|--------------------------------------------------------------------------
|
| Provide the csrf guard service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Csrf\Guard;


class CsrfServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['csrf'];

    public function register()
    {
        $container = $this->container;
        $container->share('csrf', function () use ($container) {
            $guard = new Guard;
            $guard->setFailureCallable(function(Request $request, Response $response, $next) use ($container) {
                return $container->get('view')->render($response, 'error.twig', [
                    'code' => 500,
                    'msg' => 'You cannot reload the page',
                ]);
            });
            return $guard;
        });
    }
}
