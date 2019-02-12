<?php
/*
|--------------------------------------------------------------------------
| Public Index
|--------------------------------------------------------------------------
|
| Where the user will land
|
*/

define('APP_START', microtime(true));

require __DIR__ . '/../bootstrap/app.php';

$app->run();
