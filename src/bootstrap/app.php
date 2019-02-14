<?php
/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
|
| Bootstrap the application
|
*/

require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::create(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
$dotenv->load();

$settings = require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'settings.php';

$app = new Jenssegers\Lean\App();

$container = $app->getContainer();
$container->inflector(App\Controllers\Controller::class)->invokeMethod('setContainer', [$container]);
$container->get('settings')->replace($settings);

setLocale(LC_ALL, $settings['locale']);

require __DIR__ . '/../app/dependencies.php';

require __DIR__ . '/../app/Routes/index.php';
