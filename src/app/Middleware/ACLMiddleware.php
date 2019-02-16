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
use App\Models\Role;


class ACLMiddleware extends Middleware
{
    private $router;
    private $auth;
    private $roles;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->router = $container->get('router');
        $this->auth = $container->get('auth');
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
                'role' => '*',
                'path' => '/',
                'method' => ['GET'],
            ],
            [
                'role' => '*',
                'path' => '[]',
                'method' => ['GET'],
            ],
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
                'path' => '/admin/users/restore/',
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

        if(!$route)
            return $next($request, $response);

        $path = $route->getPattern();
        $method = $request->getMethod();

        $accessRule = $this->ACL($path, $method);

        if(!$accessRule)
            return $this->denyAccess($response, $accessRule['path']);

        if($this->auth->check())
            $userRole = $this->auth->user()->role->level;

        if(!$this->checkUserRole($accessRule['role'], $userRole))
            return $this->denyAccess($response, $accessRule['path']);

        return $next($request, $response);

    }
}
