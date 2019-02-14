<?php
/*
|--------------------------------------------------------------------------
| CustomErrorHandlerServiceProvider
|--------------------------------------------------------------------------
|
| Provide the custom error handler service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Helpers\CustomErrorHandler;


class CustomErrorHandlerServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['customErrorHandler'];

    public function register()
    {
        $container = $this->container;
        $container->share('customErrorHandler', function () use ($container) {
            return new CustomErrorHandler($container->get('view'));
        });
    }
}
