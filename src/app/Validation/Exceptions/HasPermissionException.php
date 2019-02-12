<?php
/*
|--------------------------------------------------------------------------
| Validator HasPermission Exception
|--------------------------------------------------------------------------
|
| If the validator HasPermission fails, it gets this error value
|
*/

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;


class HasPermissionException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'You cannot assign this {{name}}'
        ],
    ];
}
