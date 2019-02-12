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


class Controller
{
    protected $container;

    /**
     * Constructor
     *
     * @param \Slim\Container $container App $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if($this->container->{$property})
            return $this->container->{$property};
    }
}
