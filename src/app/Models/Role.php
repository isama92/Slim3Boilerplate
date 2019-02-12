<?php
/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
|
| Role model
|
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Respect\Validation\Validator as v;


class Role extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->hasMany('\App\Models\User');
    }

    public static function validators()
    {
        return [
            'name' => v::stringType()->notEmpty()->notBlank()->length(1, 45),
            'description' => v::optional(v::stringType()->length(1, 191)),
            'level' => v::numeric()->notEmpty()->between(1, 255),
        ];
    }

    public static function getRolesByUser($user)
    {
        return Role::where('level', '<=', $user->role->level)->orderBy('level', 'ASC')->get();
    }
}
