<?php
/*
|--------------------------------------------------------------------------
| FlashServiceProvider
|--------------------------------------------------------------------------
|
| Provide the flash service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Flash\Messages;


class FlashServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['flash'];

    public function register()
    {
        $this->container->share('flash', function () {
            return new Messages;
        });
    }
}
