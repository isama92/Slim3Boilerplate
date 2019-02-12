<?php
/*
|--------------------------------------------------------------------------
| Utility helper
|--------------------------------------------------------------------------
|
| Various utility functions
|
*/

namespace App\Helpers;


class Utility
{
    public static function delete_all_between($beginning, $end, $str) {
        $beginningPos = strpos($str, $beginning);
        $endPos = strpos($str, $end);

        if ($beginningPos === false || $endPos === false)
            return $str;

        $textToDelete = substr($str, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return str_replace($textToDelete, '', $str);
    }
}
