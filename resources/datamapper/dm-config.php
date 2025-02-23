<?php

return [
    'pdo'       => [
        'dsn'      => "mysql:host=phalcon-mysql;dbname=phalcon;charset=utf8mb4;port=3306",
        'username' => 'phalcon',
        'password' => 'secret',
    ],
    'namespace'   => 'Phalcon\DataMapper',
    'outputDir'   => getcwd() . '/resources/test/',
    'templateDir' => getcwd() . '/resources/templates/',
];
