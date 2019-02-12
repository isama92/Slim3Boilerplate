<?php
/*
|--------------------------------------------------------------------------
| Verify Old Password Exception
|--------------------------------------------------------------------------
|
| If the validator VerifyOldPasswordException fails, it gets this error value
|
*/

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;


class VerifyOldPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} is not correct'
        ],
    ];
}
