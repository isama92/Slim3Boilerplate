<?php
/*
|--------------------------------------------------------------------------
| Validator RoleExists Exception
|--------------------------------------------------------------------------
|
| If the validator RoleExists fails, it gets this error value
|
*/

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;


class RoleExistsException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'This {{name}} do not exists'
        ],
    ];
}
