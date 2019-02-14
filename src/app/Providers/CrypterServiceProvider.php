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
use App\Helpers\Crypter;


class CrypterServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['crypter'];

    public function register()
    {
        $container = $this->container;
        $container->share('crypter', function () use ($container) {
            return new Crypter($container->get('settings'));
        });
    }
}
