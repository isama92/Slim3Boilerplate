<?php
/*
|--------------------------------------------------------------------------
| AuthServiceProvider
|--------------------------------------------------------------------------
|
| Provide the auth service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Auth\Auth;


class AuthServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['auth'];

    public function register()
    {
        $container = $this->container;
        $container->share('auth', function () use ($container) {
            return new Auth($container->get('session'));
        });
    }
}
