<?php
/*
|--------------------------------------------------------------------------
| LoggerServiceProvider
|--------------------------------------------------------------------------
|
| Provide the logger service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Helpers\Logger;


class LoggerServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['logger'];

    public function register()
    {
        $container = $this->container;
        $container->share('logger', function () use ($container) {
            return new Logger($container->get('settings'), $container->get('auth'));
        });
    }
}
