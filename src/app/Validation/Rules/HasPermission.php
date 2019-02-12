<?php
/*
|--------------------------------------------------------------------------
| HasPermission Validator Rule
|--------------------------------------------------------------------------
|
| A new rule to validate if the user has the permission to use the role id
|
*/

namespace App\Validation\Rules;

use App\Models\Role;
use Respect\Validation\Rules\AbstractRule;


class HasPermission extends AbstractRule
{
    protected $role_level;

    /**
     * Constructor
     *
     * @param integer $role_level Role leve of user who is validating this role
     */
    public function __construct($role_level)
    {
        $this->role_level = $role_level;
    }

    /**
     * Validate the value
     *
     * @param integer $role_id new role id
     * @return boolean true if the user has enough permission to save this role else false
     */
    public function validate($role_id)
    {
        $new_role = Role::find($role_id);
        return $new_role->level <= $this->role_level;
    }
}
