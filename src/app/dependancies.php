<?php
/*
|--------------------------------------------------------------------------
| Dependancies
|--------------------------------------------------------------------------
|
| Where the container dependancies get loaded
|
*/

$container = $app->getContainer();

\Illuminate\Pagination\LengthAwarePaginator::viewFactoryResolver(function() { return new \App\View\Factory; });
\Illuminate\Pagination\LengthAwarePaginator::defaultView('partials/pagination.twig');

\Illuminate\Pagination\Paginator::currentPathResolver(function() {
    return isset($_SERVER['REQUEST_URI'])? strtok($_SERVER['REQUEST_URI'], '?') : '/';
});

\Illuminate\Pagination\Paginator::currentPageResolver(function() {
    return $_GET['page']?? 1;
});

$container['session'] = function() { return new \SlimSession\Helper; };
$container['csrf'] = function($container) {
    $guard = new \Slim\Csrf\Guard;
    $guard->setFailureCallable(function(\Slim\Http\Request $request, \Slim\Http\Response $response, $next) use ($container) {
        return $container->view->render($response, 'error.twig', [
            'code' => 500,
            'msg' => 'You cannot reload the page',
        ]);
    });
    return $guard;
};
$container['validator'] = function() { return new App\Validation\Validator; };
$container['flash'] = function() { return new \Slim\Flash\Messages; };
$container['errorH'] = function($container) { return new \App\Helpers\Error($container); };
$container['auth'] = function($container) { return new \App\Auth\Auth($container); };
$container['logger'] = function($container) { return new \App\Helpers\Logger($container); };

// Controllers
$container['HomeController'] = function ($container) { return new \App\Controllers\HomeController($container); };
$container['AuthController'] = function ($container) { return new \App\Controllers\AuthController($container); };
$container['UsersController'] = function ($container) { return new \App\Controllers\UsersController($container); };

$app->add(new \App\Middleware\ValidationErrorMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

// Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function () use ($capsule){ return $capsule; };

// Twig
$container['view'] = function ($container) {
    $view = \App\View\Factory::getEngine($container['settings']);

    $view->addExtension(new Slim\Views\TwigExtension($container->router, $container->request->getUri()));
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);
    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

$app->add(new \App\Middleware\ACLMiddleware($container));
$app->add(new \Slim\Middleware\Session($settings['session']));
