<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\App;

use PDO;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class DatabaseTest extends TestCase
{
    function testConnection()
    {
        $pdo = Database::getConnection();
        self::assertNotNull($pdo);
        self::assertInstanceOf(PDO::class, $pdo);
    }

    function testConnectionSingletone()
    {
        $pdo1 = Database::getConnection();
        $pdo2 = Database::getConnection();

        self::assertSame($pdo1, $pdo2);
    }

    function testConnectionEnv()
    {
        Database::clearConnection();
        $pdo1 = Database::getConnection('prod');
        $prod = $pdo1->query('SELECT DATABASE()')->fetchColumn();
        self::assertEquals('daily_report_tik_polresta_tuban_db', $prod);

        Database::clearConnection();

        $pdo2 = Database::getConnection('dev');
        $dev = $pdo2->query('SELECT DATABASE()')->fetchColumn();
        self::assertEquals('daily_report_tik_polresta_tuban_db_dev', $dev);
    }
}