<?php
/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
|
| Application settings file
|
*/

$debug_mode = strtolower(strval(getenv('DEBUG'))) === 'true';

return [
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header
    'determineRouteBeforeAppMiddleware' => true, // Used to read the current route objects in your middleware (auth)
    'displayErrorDetails' => $debug_mode,

    'locale' => 'en_GB.UTF8',
    'debug_mode' => $debug_mode,
    'tmp_path' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR. 'cache',



    'view' => [
        'template_path' => __DIR__ . DIRECTORY_SEPARATOR . 'Templates',
        'cache' => $debug_mode? false : __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR. 'cache' . DIRECTORY_SEPARATOR . 'twig',
    ],

    // Services
    'ipstack' => [
        'API_KEY' => getenv('IP_STACK'),
    ],

    'mailer' => [
        'template_path' => __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'email',
        'test_email' => 'isama92@gmail.com',
        'from' => [
            'address' => 'isama92@gmail.com',
            'name' => 'Slim 3 Boilerplate',
        ],
    ],

    // Session
    'session' => [
        'name' => 'auth',
        'autorefresh' => true,
        'lifetime' => '1 hour',
    ],

    // Eloquent
    'db' => [
        'driver' => 'mysql',
        'host' => getenv('DB_HOST'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
        'database' => getenv('DB_DATABASE'),
        'charset' => getenv('DB_CHARSET'),
        'collation' => getenv('DB_COLLATION'),
        'prefix' => getenv('DB_PREFIX'),
    ],

    // Crypter
    'crypter' => [
        'key' => getenv('CRYPTER_KEY'),
        'cipher' => 'AES-128-CBC',
        'hash_alg' => 'sha256',
    ],
];
