<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        'db' => [
            // PDO database configuration
            'driver'    => 'mysql',
            'host'      => '192.168.56.103',
            'database'  => 'gi_test_db',
            'username'  => 'user',
            'password'  => 'pass',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
    ],
];
