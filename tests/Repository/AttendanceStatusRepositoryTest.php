<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;

class AttendanceStatusRepositoryTest extends TestCase
{
    private AttendanceStatusRepository $repo;

    protected function setUp(): void
    {
        Database::clearConnection();
        $pdo = Database::getConnection();
        $this->repo = new AttendanceStatusRepository($pdo);
    }

    public function testFindAllReturnsSevenStatuses(): void
    {
        $result = $this->repo->findAll();
        self::assertCount(11, $result);
    }

    public function testFindAllSortedByOrder(): void
{
    $result = $this->repo->findAll();

    self::assertSame('H',       $result[0]->code);
    self::assertSame('D',       $result[1]->code);
    self::assertSame('P',       $result[2]->code);
    self::assertSame('DIKSIP',  $result[3]->code);
    self::assertSame('DIK',     $result[4]->code);
    self::assertSame('PATSUS',  $result[5]->code);
    self::assertSame('LD',      $result[6]->code);
    self::assertSame('IZIN',    $result[7]->code);
    self::assertSame('CUTI',    $result[8]->code);
    self::assertSame('SKT',     $result[9]->code);
    self::assertSame('TK',      $result[10]->code);
}

    public function testFindByCode(): void
    {
        $result = $this->repo->findByCode('H');
        self::assertNotNull($result);
        self::assertSame('Hadir', $result->label);
    }

    public function testFindByCodeNotFound(): void
    {
        $result = $this->repo->findByCode('XXX');
        self::assertNull($result);
    }

    public function testExistsByCode(): void
    {
        self::assertTrue($this->repo->existsByCode('H'));
        self::assertTrue($this->repo->existsByCode('IZIN'));
        self::assertFalse($this->repo->existsByCode('XXX'));
    }
}