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
use App\Helpers\Mailer;


class MailerServiceProvider extends AbstractServiceProvider
{
    protected $provides = ['mailer'];

    public function register()
    {
        $container = $this->container;
        $container->share('mailer', function () use ($container) {
            return new Mailer($container->get('settings'));
        });
    }
}
