<?php
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
|
| Auth class
|
*/

namespace App\Auth;

use App\Models\User;


class Auth
{
    private $session;

    public function __construct($container)
    {
        $this->session = $container->session;
    }

    public function attempt($email, $password)
    {
        $user = User::where('email', $email)->first();
        if(!$user)
            return false;
        if($user->verifyPassword($password)) {
            $this->session->set('user', $user->id);
            return true;
        }
        return false;
    }

    public function check()
    {
        return $this->session->exists('user');
    }

    public function user()
    {
        if($this->check())
            return User::find($this->session->user);
        else
            return false;
    }
}