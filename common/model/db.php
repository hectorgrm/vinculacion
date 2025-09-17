<?php

declare(strict_types=1);

namespace Common\Model;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $configPath = __DIR__ . '/../../config/db.php';
        $config = require $configPath;

        if (!is_array($config)) {
            throw new PDOException('Invalid database configuration.');
        }

        $host = (string)($config['host'] ?? 'localhost');
        $dbName = (string)($config['dbname'] ?? '');
        $user = (string)($config['user'] ?? 'root');
        $password = (string)($config['pass'] ?? '');
        $charset = (string)($config['charset'] ?? 'utf8mb4');
        $port = (string)($config['port'] ?? '3306');

        if ($dbName === '') {
            throw new PDOException('Database name is required.');
        }

        $dsn = sprintf('mysql:host=%s;dbname=%s;port=%s;charset=%s', $host, $dbName, $port, $charset);

        self::$connection = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$connection;
    }
}
