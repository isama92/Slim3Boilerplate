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

        while($beginningPos !== false || $endPos !== false) {
            $textToDelete = substr($str, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
            $str = str_replace($textToDelete, '', $str);
            $beginningPos = strpos($str, $beginning);
            $endPos = strpos($str, $end);
        }

        return $str;
    }
}
