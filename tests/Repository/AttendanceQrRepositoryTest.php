<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;

class AttendanceQrRepositoryTest extends TestCase
{
    private AttendanceQrRepository $repo;

    protected function setUp(): void
    {
        Database::clearConnection();
        $pdo = Database::getConnection();
        $this->repo = new AttendanceQrRepository($pdo);
    }

    public function testFindByCode(): void
    {
        $result = $this->repo->findByCode('ABSEN-SITIK-TUBAN');
        self::assertNotNull($result);
        self::assertSame('QR Absen Kantor', $result->name);
        self::assertTrue($result->isActive);
    }

    public function testFindByCodeNotFound(): void
    {
        $result = $this->repo->findByCode('TIDAK-ADA');
        self::assertNull($result);
    }

    public function testExistsByCode(): void
    {
        self::assertTrue($this->repo->existsByCode('ABSEN-SITIK-TUBAN'));
        self::assertFalse($this->repo->existsByCode('TIDAK-ADA'));
    }

    public function testFindActive(): void
    {
        $result = $this->repo->findActive();
        self::assertNotEmpty($result);
    }
}