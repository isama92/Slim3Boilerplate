<?php
/*
|--------------------------------------------------------------------------
| Admin user routes
|--------------------------------------------------------------------------
|
| Admin user routes
|
*/

use App\Controllers\UsersController;

$this->group('/users', function () {
    $this->get('/', UsersController::class . ':index')->setName('users');
    $this->post('/', UsersController::class . ':filter')->setName('users_filter');
    $this->get('/filters_reset', UsersController::class . ':resetFilters')->setName('users_filter_reset');
    $this->get('/create', UsersController::class . ':form')->setName('users_create');
    $this->post('/create', UsersController::class . ':create')->setName('users_create_save');
    $this->get('/update/{id}', UsersController::class . ':form')->setName('users_update');
    $this->post('/update/{id}', UsersController::class . ':update')->setName('users_update_save');
    $this->get('/delete/{id}', UsersController::class . ':delete')->setName('users_delete');
    $this->get('/login_as/{id}', UsersController::class . ':loginAs')->setName('users_login_as');
});
