<?php
return [
    /*'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
    'port' => env('MAIL_PORT', 587),
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'sendmail' => '/usr/sbin/sendmail -bs',*/
    'driver'     => env('MAIL_DRIVER', 'smtp'),
    'host'       => env('MAIL_HOST', 'smtp.gmail.com'),
    'port'       => env('MAIL_PORT', 587),
    'from'       => ['address' =>'nursingcare@gmail.com', 'name' => '24*7NursingCare'],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username'   => env('MAIL_USERNAME','kajal.garg@saffrontech.net'),
    'password'   => env('MAIL_PASSWORD','kajal@844'),
    'sendmail'   => '/usr/sbin/sendmail -bs',

    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],
    'log_channel' => env('MAIL_LOG_CHANNEL'),
];