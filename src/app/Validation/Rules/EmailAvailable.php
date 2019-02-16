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
    protected $oldEmail;

    /**
     * Constructor
     *
     * @param string $email Old email
     */
    public function __construct($email = null)
    {
        $this->oldEmail = $email;
    }

    /**
     * Validate the value
     *
     * @param string $email the new email
     * @return boolean true if the email do not exists else false
     */
    public function validate($email)
    {
        if($this->oldEmail === $email)
            return true;
        return User::where('email', $email)->count() === 0;
    }
}
