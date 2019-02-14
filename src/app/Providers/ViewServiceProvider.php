<?php
/*
|--------------------------------------------------------------------------
| ViewServiceProvider
|--------------------------------------------------------------------------
|
| Provide the view service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use \App\View\Factory;
use \Slim\Views\TwigExtension;


class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['view'];

    public function register()
    {
        $container = $this->container;

        $container->share('view', function () use ($container) {
            $view = Factory::getEngine($container->get('settings'));
            $view->addExtension(new TwigExtension($container->get('router'), $container->get('request')->getUri()));

            $view->getEnvironment()->addGlobal('auth', [
                'check' => $container->get('auth')->check(),
                'user' => $container->get('auth')->user(),
            ]);
            $view->getEnvironment()->addGlobal('flash', $container->get('flash'));

            return $view;
        });
    }
}
