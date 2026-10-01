<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\App;

use PDO;

class Database
{
    private static ?PDO $pdo = null;
    public static function getConnection(string $env = 'dev')
    {
        if (self::$pdo === null) {
            require_once __DIR__ . '/../../config/database.php';
            $dbConfig = getDatabaseConfig($env);
            self::$pdo = new PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password']);
        }

        return self::$pdo;
    }

    public static function clearConnection()
    {
        self::$pdo = null;
    }
}
