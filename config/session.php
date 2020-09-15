<?php

use Illuminate\Support\Str;

return [

    'driver' => 'cookie',

    'lifetime' => 20*(60*24*365),

    'expire_on_close' => true,

    'encrypt' => true,

    'connection' => env('SESSION_CONNECTION', null),


    'table' => 'sessions',


    'store' => env('SESSION_STORE', null),


    'lottery' => [2, 100],


    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),


    'path' => '/',


    'domain' => env('SESSION_DOMAIN', null),


    'secure' => env('SESSION_SECURE_COOKIE'),


    'http_only' => true,


    'same_site' => 'lax',

];
