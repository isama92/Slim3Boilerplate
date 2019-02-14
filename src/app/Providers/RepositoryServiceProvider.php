<?php
/*
|--------------------------------------------------------------------------
| RepositoryServiceProvider
|--------------------------------------------------------------------------
|
| Provide repositories for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\Eloquent\EloquentUserRepository;


class RepositoryServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        UserRepository::class,
    ];

    public function register()
    {
        $container = $this->container;
        $container->share(UserRepository::class, function() use ($container) {
            return new EloquentUserRepository($container);
        });
    }
}
