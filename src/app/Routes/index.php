<?php
/*
|--------------------------------------------------------------------------
| Index routes
|--------------------------------------------------------------------------
|
| Index routes
|
*/

require __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'index.php';
$app->get('/', 'HomeController:index')->setName('home');
$app->get('[{path:.*}]', 'HomeController:error404')->setName('error404');
