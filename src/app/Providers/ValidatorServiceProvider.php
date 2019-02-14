<?php
/*
|--------------------------------------------------------------------------
| ValidatorServiceProvider
|--------------------------------------------------------------------------
|
| Provide the validator service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Validation\Validator;


class ValidatorServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['validator'];

    public function register()
    {
        $this->container->share('validator', function () {
            return new Validator;
        });
    }
}
