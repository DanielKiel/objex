<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 19:44
 */

return [
    'connection' => [
        'driver'   => 'pdo_mysql',
        'user'     => 'root',
        'password' => '',
        'dbname'   => 'objex',
    ],
    'entity_paths' => [
        __DIR__ . '/../modules/DBStorage/Models'
    ],
    'cache' => [
        'adapter' => new \Symfony\Component\Cache\Adapter\FilesystemAdapter(),
        /* optimize: choose between read or write - dependent to the config DBStorage will decide
         *  when to aggregate an object and cache the result
         */
        'optimize' => 'read'
    ]
];