<?php
/*
|--------------------------------------------------------------------------
| Admin index routes
|--------------------------------------------------------------------------
|
| Admin index routes
|
*/

use App\Controllers\AuthController;

$app->group('/admin', function () {
    require __DIR__ . DIRECTORY_SEPARATOR . 'users.php';
    $this->get('[/]', AuthController::class . ':home')->setName('admin');
    $this->get('/login', AuthController::class . ':login')->setName('login');
    $this->post('/login', AuthController::class . ':auth')->setName('auth');
    $this->get('/logout', AuthController::class . ':logout')->setName('logout');
    $this->get('/change_password', AuthController::class . ':changePasswordForm')->setName('change_password');
    $this->post('/change_password', AuthController::class . ':changePassword')->setName('change_password_save');
});
