<?php
/*
|--------------------------------------------------------------------------
| Users Controller
|--------------------------------------------------------------------------
|
| Users controller
|
*/

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User;
use App\Models\Role;


class UsersController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $where = [];
        $filters = [];
        if($this->session->exists('users_filters')) {
            $filters = $this->session->users_filters;
            foreach($this->session->users_filters as $k => $v)
                $where[] = [$k, 'LIKE', '%' . $v . '%'];
        }

        $orderBy = 'created_at';
        $orderType = 'ASC';

        if($request->getParam('ordb'))
            $orderBy = $request->getParam('ordb');

        if($request->getParam('ordt'))
            $orderType = $request->getParam('ordt');

        $users = User::where($where)->orderBy($orderBy, $orderType)->paginate(12)->appends($request->getParams());

        return $this->view->render($response, 'admin/users/list.twig', [
            'users' => $users,
            'filters' => $filters,
            'orderBy' => $orderBy,
            'orderType' => $orderType,
        ]);
    }

    public function indexJson(Request $request, Response $response)
    {
        $where = [];
        if($this->session->exists('users_filters')) {
            foreach($this->session->users_filters as $k => $v)
                $where[] = [$k, 'LIKE', '%' . $v . '%'];
        }

        $users = User::where($where)->orderBy('created_at', 'ASC')->paginate(12);

        return $response->withJson([
            'users' => $users->getCollection(),
            'meta' => [
                'pagination' => array_except($users->toArray(), ['data']),
            ],
        ]);
    }

    public function filter(Request $request, Response $response)
    {
        $filters = array_filter($request->getParams(), function($filter) {
            return strpos($filter, 'csrf') === false;
        }, ARRAY_FILTER_USE_KEY);
        $this->session->set('users_filters', $filters);
        return $response->withRedirect($this->router->pathFor('users'));
    }

    public function resetFilters(Request $request, Response $response)
    {
        if($this->session->exists('users_filters'))
            $this->session->delete('users_filters');
        return $response->withRedirect($this->router->pathFor('users'));
    }

    public function form(Request $request, Response $response, $args)
    {

        $user = null;
        if(isset($args['id'])) {
            $user = User::find($args['id']);
            if(!$user) {
                $this->flash->addMessage('error', 'User do not exists');
                return $response->withRedirect($this->router->pathFor('users'));
            }
        }
        return $this->view->render($response, 'admin/users/form.twig', [
            'user' => $user,
            'roles' => Role::getRolesByUser($this->auth->user()),
        ]);
    }

    public function create(Request $request, Response $response)
    {
        $POST = $request->getParsedBody();
        $validation = $this->validator->massValidate($request, User::validatorsCreate($request, $this->auth->user()));
        if(!$validation->failed()) {
            $user = new User;
            $user->fill($POST);
            $user->setPassword($POST['password']);
            if(isset($POST['role_id']))
                $user->setRole($this->auth->user()->role, $POST['role_id']);
            if($user->save()) {
                $this->flash->addMessage('success', 'User created successfully');
                $this->logger->log('USER_CREATE', $user->id);
                return $response->withRedirect($this->router->pathFor('users'));
            }
            $this->flash->addMessage('error', 'An error has occurred');
        }
        return $response->withRedirect($this->router->pathFor('users_create'));
    }

    public function update(Request $request, Response $response, $args)
    {
        $POST = $request->getParsedBody();
        $user = User::find($args['id']);
        if(!$user) {
            $this->flash->addMessage('error', 'User do not exists');
            return $response->withRedirect($this->router->pathFor('users'));
        }
        $validation = $this->validator->massValidate($request, User::validatorsUpdate($request, $this->auth->user(), $user));
        if(!$validation->failed()) {
            $user->fill($POST);
            if(isset($POST['password']) && $POST['password'] !== '')
                $user->setPassword($POST['password']);
            if(isset($POST['role_id']))
                $user->setRole($this->auth->user()->role, $POST['role_id']);
            if($user->save()) {
                $this->flash->addMessage('success', 'User updated successfully');
                $this->logger->log('USER_UPDATE', $user->id);
                return $response->withRedirect($this->router->pathFor('users'));
            }
            $this->flash->addMessage('error', 'An error has occurred');
        }
        return $response->withRedirect($this->router->pathFor('users_update', ['id' => $args['id']]));
    }

    public function delete(Request $request, Response $response, $args)
    {
        $user = User::find($args['id']);
        if($user) {
            $user->delete();
            $this->flash->addMessage('success', 'User has been deleted successfully');
            $this->logger->log('USER_DELETE', $user->id);
        } else
            $this->flash->addMessage('error', 'User do not exists');
        return $response->withRedirect($this->router->pathFor('users'));
    }

    public function loginAs(Request $request, Response $response, $args)
    {
        $user = User::find($args['id']);
        if(!$user) {
            $this->flash->addMessage('error', 'User do not exists');
            return $response->withRedirect($this->router->pathFor('users'));
        }
        $this->session->set('user', $user->id);
        $this->logger->log('LOGIN_AS', $user->id);
        return $response->withRedirect($this->router->pathFor('admin'));
    }
}
