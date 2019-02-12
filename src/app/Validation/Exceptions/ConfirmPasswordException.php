<?php
/*
|--------------------------------------------------------------------------
| Validator ConfirmPassword Exception
|--------------------------------------------------------------------------
|
| If the validator ConfirmPassword fails, it gets this error value
|
*/

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;


class ConfirmPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} do not match'
        ],
    ];
}
