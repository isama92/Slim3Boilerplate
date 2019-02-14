<?php
/*
|--------------------------------------------------------------------------
| DatabaseServiceProvider
|--------------------------------------------------------------------------
|
| Provide the database service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;


class DatabaseServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = ['view'];

    public function register() {}

    public function boot()
    {
        $container = $this->getContainer();

        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($container->get('settings')->get('db'));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $container->share('db', function() use ($capsule) {
            return $capsule;
        });
    }
}
