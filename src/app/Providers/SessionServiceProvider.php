<?php
/*
|--------------------------------------------------------------------------
| SessionServiceProvider
|--------------------------------------------------------------------------
|
| Provide the session service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use SlimSession\Helper;


class SessionServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['session'];

    public function register()
    {
        $this->container->share('session', function () {
            return new Helper;
        });
    }
}
