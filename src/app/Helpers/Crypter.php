<?php
/*
|--------------------------------------------------------------------------
| Crypter helper
|--------------------------------------------------------------------------
|
| Crypter Helper
|
*/

namespace App\Helpers;

use SlimFacades\App;


class Crypter
{
    /**
     * Hash the given password
     *
     * @param string $psw Password to hash
     * @return string Hashed password
     */
    public static function hashPassword($psw)
    {
        if($psw !== '' && password_needs_rehash($psw, PASSWORD_DEFAULT))
            $psw = password_hash($psw, PASSWORD_DEFAULT);
        return $psw;
    }

    /**
     * Encrypt the given string
     *
     * @param string $str String to encrypt
     * @return string Crypted string
     */
    public static function encrypt($plaintext)
    {
        $settings = App::getContainer()['settings'];
        $cipher = $settings['crypter']['cipher'];
        $key = $settings['crypter']['key'];
        $hash_alg = $settings['crypter']['hash_alg'];

        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac($hash_alg, $ciphertext_raw, $key, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        return $ciphertext;
    }

    /**
     * Decrypt the given string
     *
     * @param string $str String to decrypt
     * @return string Decrypted string
     */
    public static function decrypt($ciphertext)
    {
        $settings = App::getContainer()['settings'];
        $cipher = $settings['crypter']['cipher'];
        $key = $settings['crypter']['key'];
        $hash_alg = $settings['crypter']['hash_alg'];

        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac($hash_alg, $ciphertext_raw, $key, $as_binary=true);
        if (hash_equals($hmac, $calcmac))
            return $original_plaintext;
        return false;
    }

    public static function generateString($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($x=$characters, ceil($length/strlen($x)) )),1,$length);
    }
}
