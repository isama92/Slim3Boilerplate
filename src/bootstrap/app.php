<?php
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Bootstrap the application
|
*/

require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::create(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
$dotenv->load();

$settings = require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'settings.php';
$app = new \Slim\App($settings);

$settings = $settings['settings'];

// set locale
setLocale(LC_ALL, $settings['locale']);

\SlimFacades\Facade::setFacadeApplication($app);

// Set up dependencies
require __DIR__ . '/../app/dependancies.php';

// Routes
require __DIR__ . '/../app/Routes/index.php';
