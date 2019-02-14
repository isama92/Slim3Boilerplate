<?php
/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
|
| User model
|
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Respect\Validation\Validator as v;
v::with('App\\Validation\\Rules');


class User extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'name',
        'surname',
        'email',
    ];

    public function role()
    {
        return $this->belongsTo('\App\Models\Role')->withTrashed();
    }

    public function logs()
    {
        return $this->hasMany('\App\Models\Log');
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getCreatedAtFormattedAttribute()
    {
        $date = new \DateTime($this->created_at);
        return strftime('%Y-%m-%d %H:%M', $date->getTimestamp());
    }

    public function getDeleteAsTextAttribute()
    {
        return $this->deleted_at? 'Yes' : 'No';
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function setRole($permissionRole, $newRoleId) {
        $newRole = Role::find($newRoleId);
        if($newRole && $newRole->level <= $permissionRole->level)
            $this->role()->associate($newRole);
    }

    public static function validatorsCreate($request, $loggedUser)
    {
        return [
            'name' => v::stringType()->notEmpty()->notBlank()->alpha()->length(1, 100),
            'surname' => v::stringType()->notEmpty()->notBlank()->alpha()->length(1, 100),
            'email' => v::stringType()->notEmpty()->notBlank()->email()->length(1, 100)->emailAvailable(),
            'password' => v::stringType()->notEmpty()->notBlank()->length(5, 100)->confirmPassword($request->getParam('password_confirm')),
            'role_id' => v::optional(v::numeric()->notEmpty()->roleExists()->hasPermission($loggedUser->role->level)),
        ];
    }

    public static function validatorsUpdate($request, $loggedUser, $user)
    {
        return [
            'name' => v::stringType()->notEmpty()->notBlank()->alpha()->length(1, 100),
            'surname' => v::stringType()->notEmpty()->notBlank()->alpha()->length(1, 100),
            'email' => v::stringType()->notEmpty()->notBlank()->email()->length(1, 100)->emailAvailable($user->email),
            'password' => v::optional(v::stringType()->notEmpty()->notBlank()->length(5, 100)->confirmPassword($request->getParam('password_confirm'))),
            'role_id' => v::optional(v::numeric()->notEmpty()->between(1, 255)->roleExists()->hasPermission($loggedUser->role->level)),
        ];
    }

    public static function validatorsChangePassword($request, $loggedUser)
    {
        return [
            'password_old' => v::stringType()->notEmpty()->notBlank()->verifyOldPassword($loggedUser),
            'password' => v::stringType()->notEmpty()->notBlank()->length(5, 100)->confirmPassword($request->getParam('password_confirm')),
        ];
    }
}
