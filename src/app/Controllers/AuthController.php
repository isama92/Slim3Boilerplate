<?php
/*
|--------------------------------------------------------------------------
| Admin Controller
|--------------------------------------------------------------------------
|
| Admin controller
|
*/

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User;


class AuthController extends Controller
{
    public function home(Request $request, Response $response)
    {
        return $this->view->render($response, 'admin/home.twig');
    }

    public function login(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/login.twig');
    }

    public function auth(Request $request, Response $response)
    {
        $auth = $this->auth->attempt($request->getParam('email'), $request->getParam('password'));
        if(!$auth) {
            $this->flash->addMessage('error', 'Login has failed');
            return $response->withRedirect($this->router->pathFor('login'));
        }
        return $response->withRedirect($this->router->pathFor('admin'));
    }

    public function logout(Request $request, Response $response)
    {
        $this->session::destroy();
        return $response->withRedirect($this->router->pathFor('login'));
    }

    public function changePasswordForm(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/change_password.twig');
    }

    public function changePassword(Request $request, Response $response)
    {
        $user = $this->auth->user();
        $validation = $this->validator->massValidate($request, User::validatorsChangePassword($request, $user));
        if(!$validation->failed()) {
            $user->setPassword($request->getParam('password'));
            if($user->save()) {
                $this->flash->addMessage('success', 'Your password was changed');
                return $response->withRedirect($this->router->pathFor('admin'));
            } else
                $this->flash->addMessage('error', 'An error has occurred');

        }
        return $response->withRedirect($this->router->pathFor('change_password'));
    }
}
