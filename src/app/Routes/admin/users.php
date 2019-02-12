<?php
/*
|--------------------------------------------------------------------------
| Admin user routes
|--------------------------------------------------------------------------
|
| Admin user routes
|
*/

$this->group('/users', function () {
    $this->get('/', 'UsersController:index')->setName('users');
    $this->post('/', 'UsersController:filter')->setName('users_filter');
    $this->get('/filters_reset', 'UsersController:resetFilters')->setName('users_filter_reset');
    $this->get('/create', 'UsersController:form')->setName('users_create');
    $this->post('/create', 'UsersController:create')->setName('users_create_save');
    $this->get('/update/{id}', 'UsersController:form')->setName('users_update');
    $this->post('/update/{id}', 'UsersController:update')->setName('users_update_save');
    $this->get('/delete/{id}', 'UsersController:delete')->setName('users_delete');
    $this->get('/login_as/{id}', 'UsersController:loginAs')->setName('users_login_as');
});
