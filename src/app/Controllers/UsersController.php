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
use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Role;


class UsersController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        // this is just an example, at the moment is not used (user repository)
        $this->users = $users;
    }

    public function index(Request $request, Response $response)
    {
        $where = [];
        $filters = [];
        if($this->container->get('session')->exists('users_filters')) {
            $filters = $this->container->get('session')->users_filters;
            foreach($this->container->get('session')->users_filters as $k => $v)
                $where[] = [$k, 'LIKE', '%' . $v . '%'];
        }

        $orderBy = 'created_at';
        $orderType = 'ASC';

        if($request->getParam('ordb'))
            $orderBy = $request->getParam('ordb');

        if($request->getParam('ordt'))
            $orderType = $request->getParam('ordt');

        $users = User::withTrashed()->where($where)->orderBy($orderBy, $orderType)->paginate(12)->appends($request->getParams());

        $filter_roles = Role::orderBy('level', 'DESC')->get();

        return $this->container->get('view')->render($response, 'admin/users/list.twig', [
            'users' => $users,
            'filter_roles' => $filter_roles,
            'filters' => $filters,
            'orderBy' => $orderBy,
            'orderType' => $orderType,
            'page' => $request->getParam('page'),
        ]);
    }

    public function indexJson(Request $request, Response $response)
    {
        $where = [];
        if($this->container->get('session')->exists('users_filters'))
            foreach($this->container->get('session')->users_filters as $k => $v)
                $where[] = [$k, 'LIKE', '%' . $v . '%'];

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
        $this->container->get('session')->set('users_filters', $filters);
        return $response->withRedirect($this->container->get('router')->pathFor('users'));
    }

    public function resetFilters(Request $request, Response $response)
    {
        if($this->container->get('session')->exists('users_filters'))
            $this->container->get('session')->delete('users_filters');
        return $response->withRedirect($this->container->get('router')->pathFor('users'));
    }

    public function form(Request $request, Response $response, $id = null)
    {
        $user = null;
        if(isset($id)) {
            $user = User::find($id);
            if(!$user) {
                $this->container->get('flash')->addMessage('error', 'User do not exists');
                return $response->withRedirect($this->container->get('router')->pathFor('users'));
            }
        }
        return $this->container->get('view')->render($response, 'admin/users/form.twig', [
            'user' => $user,
            'roles' => Role::getRolesByUser($this->container->get('auth')->user()),
        ]);
    }

    public function create(Request $request, Response $response)
    {
        $POST = $request->getParsedBody();
        $authUserRole = 0;
        if($this->container->get('auth')->check())
            $authUserRole = $this->container->get('auth')->user()->role->level;

        $errorRedirect = $response->withRedirect($this->container->get('router')->pathFor('users_create'));
        $validation = $this->container->get('validator')->massValidate($request, User::validatorsCreate($request, $this->container->get('auth')->user()));

        if($validation->failed())
            return $errorRedirect;

        $user = new User;
        $user->fill($POST);
        $user->password = $this->container->get('crypter')->hashPassword($POST['password']);

        if(!isset($POST['role_id']) || !$user->setRole($POST['role_id'], $authUserRole))
            $user->setLowestRole();

        if(!$user->save()) {
            $this->container->get('flash')->addMessage('error', 'An error has occurred');
            return $errorRedirect;
        }

        $this->container->get('flash')->addMessage('success', 'User created successfully');
        $this->container->get('logger')->log('USER_CREATE', $user->id);
        return $response->withRedirect($this->container->get('router')->pathFor('users'));
    }

    public function update(Request $request, Response $response, $id)
    {
        $POST = $request->getParsedBody();
        $user = User::find($id);
        $authUserRole = 0;
        if($this->container->get('auth')->check())
            $authUserRole = $this->container->get('auth')->user()->role->level;

        $redirectUsers = $response->withRedirect($this->container->get('router')->pathFor('users'));
        $errorRedirect = $response->withRedirect($this->container->get('router')->pathFor('users_update', ['id' => $id]));

        if(!$user) {
            $this->container->get('flash')->addMessage('error', 'User do not exists');
            return $redirectUsers;
        }



        $validation = $this->container->get('validator')->massValidate($request, User::validatorsUpdate($request, $this->container->get('auth')->user(), $user));

        if(!$validation->failed())
            return $errorRedirect;


        $user->fill($POST);

        if(isset($POST['password']) && $POST['password'] !== '')
            $user->password = $this->container->get('crypter')->hashPassword($POST['password']);

        if(isset($POST['role_id']))
            $user->setRole($POST['role_id'], $authUserRole);

        if(!$user->save()) {
            $this->container->get('flash')->addMessage('error', 'An error has occurred');
            return $errorRedirect;
        }

        $this->container->get('flash')->addMessage('success', 'User updated successfully');
        $this->container->get('logger')->log('USER_UPDATE', $user->id);
        return $redirectUsers;
    }

    public function delete(Request $request, Response $response, $id)
    {
        $user = User::find($id);
        if($user) {
            $user->delete();
            $this->container->get('flash')->addMessage('success', 'User has been deleted successfully');
            $this->container->get('logger')->log('USER_DELETE', $user->id);
        } else
            $this->container->get('flash')->addMessage('error', 'User do not exists');
        return $response->withRedirect($this->container->get('router')->pathFor('users'));
    }

    public function restore(Request $request, Response $response, $id)
    {
        $user = User::withTrashed()->find($id);
        if($user) {
            $user->restore();
            $this->container->get('flash')->addMessage('success', 'User has been restored successfully');
            $this->container->get('logger')->log('USER_RESTORE', $user->id);
        } else
            $this->container->get('flash')->addMessage('error', 'User do not exists');
        return $response->withRedirect($this->container->get('router')->pathFor('users'));
    }

    public function loginAs(Request $request, Response $response, $id)
    {
        $user = User::find($id);
        if(!$user) {
            $this->container->get('flash')->addMessage('error', 'User do not exists');
            return $response->withRedirect($this->container->get('router')->pathFor('users'));
        }
        $this->container->get('session')->set('user', $user->id);
        $this->container->get('logger')->log('LOGIN_AS', $user->id);
        return $response->withRedirect($this->container->get('router')->pathFor('admin'));
    }
}
