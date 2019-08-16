<?php
return [

    /*
    |--------------------------------------------------------------------------
    | appid length , default 16
    |--------------------------------------------------------------------------
    |
    |
    */
    'appid_length'  => env('API_KEY_APPID_LENGTH', '16'),

    /*
    |--------------------------------------------------------------------------
    | secret length , default 64
    |--------------------------------------------------------------------------
    |
    |
    */
    'secret_length' => env('API_KEY_SECRET_LENGTH', '64'),

    /*
    |--------------------------------------------------------------------------
    | Logger Setting
    |--------------------------------------------------------------------------
    | Available Drivers: "datebase", "file"
    |
    */
    'logger'        => [
        'is_taken' => env('API_KEY_LOGGER', false),
        'driver'   => env('API_KEY_LOGGER_DRIVER', 'database'),
    ],

];