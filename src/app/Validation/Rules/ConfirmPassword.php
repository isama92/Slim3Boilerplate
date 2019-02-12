<?php
/*
|--------------------------------------------------------------------------
| Confirm Password Validator Rule
|--------------------------------------------------------------------------
|
| A new rule to validate the repeat password field
|
*/

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;


class ConfirmPassword extends AbstractRule
{
    protected $password;

    /**
     * Constructor
     *
     * @param string $password Repeat password's value
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Validate the value
     *
     * @param string $password Password's value
     * @return boolean true if the validation has been successful else false
     */
    public function validate($password)
    {
        return $password === $this->password;
    }
}
