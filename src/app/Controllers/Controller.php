<?php
/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| A Controller model to extends
|
*/

namespace App\Controllers;



abstract class Controller
{
    protected $container;

    public function setContainer(\League\Container\Container $container)
    {
        $this->container = $container;
    }
}
