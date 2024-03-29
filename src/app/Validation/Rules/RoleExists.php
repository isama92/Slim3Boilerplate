<?php
/*
|--------------------------------------------------------------------------
| RoleExists Validator Rule
|--------------------------------------------------------------------------
|
| A new rule to validate if the role id exists
|
*/

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Role;


class RoleExists extends AbstractRule
{
    /**
     * Validate the value
     *
     * @param integer $role_id new role id
     * @return boolean true if the role exists else false
     */
    public function validate($role_id)
    {
        return Role::where('id', $role_id)->count() === 1;
    }
}
