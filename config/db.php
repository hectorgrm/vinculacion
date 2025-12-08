<?php

// return [
//     'host'    => '65.99.225.140',
//     'dbname'  => 'nessuste_vinculacion',   // BD asignada
//     'user'    => 'nessuste_userVinculacion',
//     'pass'    => 'F+R%&lEZdr#BY2E$',
//     'charset' => 'utf8mb4',
//     'port'    => '3306',
// ];


return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_NAME') ?: 'nessuste_vinculacion',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: 'root',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    'port' => getenv('DB_PORT') ?: '3306',
];
