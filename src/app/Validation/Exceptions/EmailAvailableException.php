<?php
/*
|--------------------------------------------------------------------------
| Validator EmailAvailable Exception
|--------------------------------------------------------------------------
|
| If the validator EmailAvailable fails, it gets this error value
|
*/

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;


class EmailAvailableException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} is already taken'
        ],
    ];
}
