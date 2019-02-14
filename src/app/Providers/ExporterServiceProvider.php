<?php
/*
|--------------------------------------------------------------------------
| MailerServiceProvider
|--------------------------------------------------------------------------
|
| Provide the mailer service for the container
|
*/

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Helpers\Exporter;


class ExporterServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['exporter'];

    public function register()
    {
        $container = $this->container;
        $container->share('exporter', function () use ($container) {
            return new Exporter($container->get('settings'));
        });
    }
}
