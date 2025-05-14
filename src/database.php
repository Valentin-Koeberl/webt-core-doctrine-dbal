<?php

return [
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'usarps_championship',
    'user'     => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
    'driverOptions' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
]; 