<?php
/*
|--------------------------------------------------------------------------
| NotFoundHandlerServiceProvider
|--------------------------------------------------------------------------
|
| Provide the not nound nandler service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Http\Request;
use Slim\Http\Response;


class NotFoundHandlerServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['notFoundHandler'];

    public function register()
    {
        $container = $this->container;
        $container->share('notFoundHandler', function () use($container) {
            return function(Request $request, Response $response) use ($container) {
                return $container->get('customErrorHandler')->render($response, 404, 'Page Not Found');
            };
        });
    }
}
