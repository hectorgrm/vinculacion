<?php

declare(strict_types=1);

namespace Common\Model;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require __DIR__ . '/../../config/db.php';

        $host    = $config['host'] ?? 'localhost';
        $db      = $config['dbname'] ?? '';
        $user    = $config['user'] ?? 'root';
        $pass    = $config['pass'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';
        $port    = $config['port'] ?? '3306';

        $dsn = "mysql:host={$host};dbname={$db};port={$port};charset={$charset}";

        try {
            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new PDOException('Error al conectar a la base de datos: ' . $exception->getMessage(), (int)$exception->getCode(), $exception);
        }

        return self::$connection;
    }
}

return Database::getConnection();
