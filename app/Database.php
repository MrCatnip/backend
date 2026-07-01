<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        // reuse one connection for the whole request
        if (self::$connection !== null) {
            return self::$connection;
        }

        $config = require dirname(__DIR__) . '/config/config.php';
        $db = $config['db'];

        $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";

        try {
            self::$connection = new PDO($dsn, $db['user'], $db['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errors throw exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // rows as ['col' => value]
                PDO::ATTR_EMULATE_PREPARES   => false,                 // use real prepared statements
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('Database connection failed: ' . $e->getMessage());
        }

        return self::$connection;
    }
}
