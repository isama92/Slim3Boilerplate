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


class Crypter
{
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }
    /**
     * Hash the given password
     *
     * @param string $psw Password to hash
     * @return string Hashed password
     */
    public function hashPassword($psw)
    {
        if($psw !== '' && password_needs_rehash($psw, PASSWORD_DEFAULT))
            $psw = password_hash($psw, PASSWORD_DEFAULT);
        return $psw;
    }

    /**
     * Encrypt the given string
     *
     * @param string $plaintext String to encrypt
     * @return string Crypted string
     */
    public function encrypt($plaintext)
    {
        $cipher = $this->settings->get('crypter.cipher');
        $key = $this->settings->get('crypter.key');
        $hash_alg = $this->settings->get('crypter.hash_alg');

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
     * @param string $ciphertext String to decrypt
     * @return string Decrypted string
     */
    public function decrypt($ciphertext)
    {
        $cipher = $this->settings->get('crypter.cipher');
        $key = $this->settings->get('crypter.key');
        $hash_alg = $this->settings->get('crypter.hash_alg');

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

    public function generateString($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($x=$characters, ceil($length/strlen($x)) )),1,$length);
    }
}
