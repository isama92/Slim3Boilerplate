<?php
/*
|--------------------------------------------------------------------------
| Admin index routes
|--------------------------------------------------------------------------
|
| Admin index routes
|
*/

use App\Middleware\ACLMiddleware;


$app->group('/admin', function () {
    require __DIR__ . DIRECTORY_SEPARATOR . 'users.php';
    $this->get('[/]', 'AuthController:home')->setName('admin');
    $this->get('/login', 'AuthController:login')->setName('login');
    $this->post('/login', 'AuthController:auth')->setName('auth');
    $this->get('/logout', 'AuthController:logout')->setName('logout');
    $this->get('/change_password', 'AuthController:changePasswordForm')->setName('change_password');
    $this->post('/change_password', 'AuthController:changePassword')->setName('change_password_save');
});
