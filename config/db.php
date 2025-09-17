<?php

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_NAME') ?: 'vinculacion',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: 'root',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    'port' => getenv('DB_PORT') ?: '3306',
];
