<?php
/*
|--------------------------------------------------------------------------
| Eloquent User Repository
|--------------------------------------------------------------------------
|
| Eloquent user repository class
|
*/

namespace App\Repositories\Eloquent;

use App\Repositories\UserRepository;
use App\Models\User;
use League\Container\Container;


class EloquentUserRepository implements UserRepository
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function all()
    {
        return User::all()->toArray();
    }
}
