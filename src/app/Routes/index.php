<?php
/*
|--------------------------------------------------------------------------
| Index routes
|--------------------------------------------------------------------------
|
| Index routes
|
*/

use App\Controllers\HomeController;

require __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'index.php';

$app->get('/', HomeController::class . ':index')->setName('home');
$app->get('[{path:.*}]', HomeController::class . ':error')->setName('error404');
