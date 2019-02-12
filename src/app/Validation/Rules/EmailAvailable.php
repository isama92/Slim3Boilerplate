<?php
/*
|--------------------------------------------------------------------------
| EmailAvailable Validator Rule
|--------------------------------------------------------------------------
|
| A rule to validate if the email is already taken
|
*/

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\User;


class EmailAvailable extends AbstractRule
{
    protected $email;

    /**
     * Constructor
     *
     * @param string $email Old email
     */
    public function __construct($email = null)
    {
        $this->email = $email;
    }

    /**
     * Validate the value
     *
     * @param string $email the new email
     * @return boolean true if the email do not exists else false
     */
    public function validate($email)
    {
        if($this->email === null || $this->email != $email)
            return User::where('email', $email)->count() === 0;
        else
            return true;
    }
}
