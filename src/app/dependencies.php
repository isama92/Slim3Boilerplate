<?php
/*
|--------------------------------------------------------------------------
| Dependencies
|--------------------------------------------------------------------------
|
| Where the container dependencies get loaded
|
*/

\Illuminate\Pagination\LengthAwarePaginator::viewFactoryResolver(function() use ($container) { return new \App\View\Factory($container->get('settings')); });
\Illuminate\Pagination\LengthAwarePaginator::defaultView('partials/pagination.twig');

\Illuminate\Pagination\Paginator::currentPathResolver(function() {
    return isset($_SERVER['REQUEST_URI'])? strtok($_SERVER['REQUEST_URI'], '?') : '/';
});

\Illuminate\Pagination\Paginator::currentPageResolver(function() {
    return $_GET['page']?? 1;
});

$container->addServiceProvider(new \App\Providers\DatabaseServiceProvider());
$container->addServiceProvider(new \App\Providers\ViewServiceProvider());
$container->addServiceProvider(new \App\Providers\FlashServiceProvider());
$container->addServiceProvider(new \App\Providers\ValidatorServiceProvider());
$container->addServiceProvider(new \App\Providers\LoggerServiceProvider());
$container->addServiceProvider(new \App\Providers\AuthServiceProvider());
$container->addServiceProvider(new \App\Providers\SessionServiceProvider());
$container->addServiceProvider(new \App\Providers\CsrfServiceProvider());
$container->addServiceProvider(new \App\Providers\CustomErrorHandlerServiceProvider());
$container->addServiceProvider(new \App\Providers\CrypterServiceProvider());
$container->addServiceProvider(new \App\Providers\MailerServiceProvider());
$container->addServiceProvider(new \App\Providers\ExporterServiceProvider());

$app->add(new \App\Middleware\ValidationErrorMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->get('csrf'));

$app->add(new \App\Middleware\ACLMiddleware($container));
$app->add(new \Slim\Middleware\Session($settings['session']));
