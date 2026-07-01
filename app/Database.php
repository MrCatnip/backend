<?php

namespace App;

use PDO;

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

        // A failed connection throws PDOException, which bubbles up to the
        // global ErrorHandler (logged, and shown per the APP_DEBUG setting).
        self::$connection = new PDO($dsn, $db['user'], $db['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errors throw exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // rows as ['col' => value]
            PDO::ATTR_EMULATE_PREPARES   => false,                 // use real prepared statements
        ]);

        return self::$connection;
    }
}
