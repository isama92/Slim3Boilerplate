<?php
/*
|--------------------------------------------------------------------------
| ACLMiddleware
|--------------------------------------------------------------------------
|
| ACL Middleware
|
*/

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Helpers\Utility;
use App\Models\User;
use App\Models\Role;


class ACLMiddleware extends Middleware
{
    private $router;
    private $auth;
    private $roles;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->router = $container->router;
        $this->auth = $container->auth;
        $this->roles = [];

        $roles = Role::all();
        $this->roles['guest'] = 0;
        foreach($roles as $role)
            $this->roles[$role->name] = $role->level;
    }

    private function ACL($path, $method)
    {
        // roles: user, admin
        // 'role' => '*', // access to all
        $accessList = [
            [
                'role' => [$this->roles['user'], $this->roles['admin']],
                'path' => '/admin[/]',
                'method' => ['GET'],
            ],
            [
                'role' => [$this->roles['guest']],
                'path' => '/admin/login',
                'method' => ['GET', 'POST'],
            ],
            [
                'role' => [$this->roles['user'], $this->roles['admin']],
                'path' => '/admin/logout',
                'method' => ['GET'],
            ],
            [
                'role' => [$this->roles['user'], $this->roles['admin']],
                'path' => '/admin/change_password',
                'method' => ['GET', 'POST'],
            ],
            [
                'role' => [$this->roles['admin']],
                'path' => '/admin/users/',
                'method' => ['GET', 'POST'],
            ],
            [
                'role' => [$this->roles['user'], $this->roles['admin']],
                'path' => '/admin/users/filters_reset',
                'method' => ['GET'],
            ],
            [
                'role' => [$this->roles['admin']],
                'path' => '/admin/users/update/',
                'method' => ['GET', 'POST'],
            ],
            [
                'role' => [$this->roles['admin']],
                'path' => '/admin/users/create',
                'method' => ['GET', 'POST'],
            ],
            [
                'role' => [$this->roles['admin']],
                'path' => '/admin/users/delete/',
                'method' => ['GET'],
            ],
            [
                'role' => [$this->roles['admin']],
                'path' => '/admin/users/login_as/',
                'method' => ['GET'],
            ],
        ];

        $path = Utility::delete_all_between('{', '}', $path);

        foreach ($accessList as $v)
            if($v['path'] === $path && in_array($method, $v['method']))
                return $v;

        return false;
    }

    private function denyAccess(Response $response, $accessPath)
    {
        $toPath = $this->router->pathFor('login');
        $res = $response->withRedirect($toPath);
        if($accessPath === $toPath)
            $res = $response->withRedirect($this->router->pathFor('admin'));
        return $res;
    }

    private function checkUserRole($accessRule, $userRole){
        return (
            $accessRule === '*' ||
            in_array($userRole, $accessRule)
        );
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $user = null;
        $userRole = $this->roles['guest'];

        $route = $request->getAttribute('route');
        $path = $route->getPattern();
        $method = $request->getMethod();

        if($this->auth->check()) {
            $user = $this->auth->user();
            if($user)
                $userRole = $user->role->level;
        }

        $accessRule = $this->ACL($path, $method);
        if($accessRule)
            if($this->checkUserRole($accessRule['role'], $userRole)) {

            }
            else
                return $this->denyAccess($response, $accessRule['path']);
        else
            return $this->denyAccess($response, $accessRule['path']);

        return $next($request, $response);
    }
}
