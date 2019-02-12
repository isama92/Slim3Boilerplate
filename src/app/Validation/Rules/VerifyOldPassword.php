<?php
/*
|--------------------------------------------------------------------------
| VerifyOldPassword Validator Rule
|--------------------------------------------------------------------------
|
| A rule to validate if the password match the current user one
|
*/

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;


class VerifyOldPassword extends AbstractRule
{
    protected $user;

    /**
     * Constructor
     *
     * @param object $user Current user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Validate the value
     *
     * @param integer $password password to verify
     * @return boolean true if the password matches the user one else false
     */
    public function validate($password)
    {
        return $this->user->verifyPassword($password);
    }
}
