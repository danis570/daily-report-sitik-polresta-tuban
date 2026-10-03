<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Report;

class ReportRepositoryTest extends TestCase
{
    private ReportRepository $reportRepository;

    protected function setUp(): void
    {
        Database::clearConnection();

        $pdo = Database::getConnection('dev');

        $this->reportRepository = new ReportRepository($pdo);

        $this->reportRepository->deleteAll();
    }

    function testSaveSuccess()
    {
        $report = new Report();

        $report->reportDate = new DateTimeImmutable('2026-05-04');
        $report->createdBy = null;

        $result = $this->reportRepository->save($report);

        self::assertNotNull($result->id);
        self::assertEquals(
            $report->reportDate,
            $result->reportDate
        );
        self::assertEquals(
            $report->createdBy,
            $result->createdBy
        );
    }

    function testFindById()
    {
        $report = new Report();

        $report->reportDate = new DateTimeImmutable('2026-05-04');
        $report->createdBy = null;

        $result = $this->reportRepository->save($report);

        $found = $this->reportRepository->findById($result->id);

        self::assertNotNull($found);
        self::assertEquals(
            $report->reportDate->format('Y-m-d'),
            $found->reportDate->format('Y-m-d')
        );
        self::assertEquals(
            $report->createdBy,
            $found->createdBy
        );
    }

    function testFindByIdNotFound()
    {
        $result = $this->reportRepository->findById(999999);

        self::assertNull($result);
    }

    function testFindByDate()
    {
        $report = new Report();

        $report->reportDate = new DateTimeImmutable('2026-05-04');
        $report->createdBy = null;

        $this->reportRepository->save($report);

        $result = $this->reportRepository->findByDate(
            new DateTimeImmutable('2026-05-04')
        );

        self::assertNotNull($result);
        self::assertEquals(
            '2026-05-04',
            $result->reportDate->format('Y-m-d')
        );
    }

    function testFindByDateNotFound()
    {
        $result = $this->reportRepository->findByDate(
            new DateTimeImmutable('2026-05-05')
        );

        self::assertNull($result);
    }

    function testCountAll()
    {
        $report1 = new Report();
        $report1->reportDate = new DateTimeImmutable('2026-05-04');
        $report1->createdBy = null;

        $report2 = new Report();
        $report2->reportDate = new DateTimeImmutable('2026-05-05');
        $report2->createdBy = null;

        $this->reportRepository->save($report1);
        $this->reportRepository->save($report2);

        $result = $this->reportRepository->countAll();

        self::assertEquals(2, $result);
    }

    function testFindAll()
    {
        $report1 = new Report();
        $report1->reportDate = new DateTimeImmutable('2026-05-04');
        $report1->createdBy = null;

        $report2 = new Report();
        $report2->reportDate = new DateTimeImmutable('2026-05-05');
        $report2->createdBy = null;

        $this->reportRepository->save($report1);
        $this->reportRepository->save($report2);

        $result = $this->reportRepository->findAll();

        self::assertCount(2, $result);

        self::assertEquals(
            '2026-05-05',
            $result[0]->reportDate->format('Y-m-d')
        );

        self::assertEquals(
            '2026-05-04',
            $result[1]->reportDate->format('Y-m-d')
        );
    }

    function testDeleteById()
    {
        $report = new Report();

        $report->reportDate = new DateTimeImmutable('2026-05-04');
        $report->createdBy = null;

        $result = $this->reportRepository->save($report);

        $deleted = $this->reportRepository->deleteById($result->id);

        self::assertTrue($deleted);

        $found = $this->reportRepository->findById($result->id);

        self::assertNull($found);
    }

    function testDeleteByIdNotFound()
    {
        $result = $this->reportRepository->deleteById(999999);

        self::assertFalse($result);
    }

    function testCountAllEmpty()
    {
        $result = $this->reportRepository->countAll();

        self::assertEquals(0, $result);
    }

    function testFindLatestEmpty()
    {
        $result = $this->reportRepository->findLatest(10);

        self::assertCount(0, $result);
    }

    function testFindLatestReturnsDescendingByDate()
    {
        $report1 = new Report();
        $report1->reportDate = new DateTimeImmutable('2026-05-04');
        $report1->createdBy = null;

        $report2 = new Report();
        $report2->reportDate = new DateTimeImmutable('2026-05-06');
        $report2->createdBy = null;

        $report3 = new Report();
        $report3->reportDate = new DateTimeImmutable('2026-05-05');
        $report3->createdBy = null;

        $this->reportRepository->save($report1);
        $this->reportRepository->save($report2);
        $this->reportRepository->save($report3);

        $result = $this->reportRepository->findLatest(10);

        self::assertCount(3, $result);

        // Urutan descending: 06, 05, 04
        self::assertEquals('2026-05-06', $result[0]->reportDate->format('Y-m-d'));
        self::assertEquals('2026-05-05', $result[1]->reportDate->format('Y-m-d'));
        self::assertEquals('2026-05-04', $result[2]->reportDate->format('Y-m-d'));
    }

    function testFindLatestRespectsLimit()
    {
        for ($i = 1; $i <= 15; $i++) {
            $report = new Report();
            $report->reportDate = new DateTimeImmutable(
                sprintf('2026-05-%02d', $i)
            );
            $report->createdBy = null;

            $this->reportRepository->save($report);
        }

        $result = $this->reportRepository->findLatest(10);

        self::assertCount(10, $result);

        // Item terbaru = tanggal 15
        self::assertEquals('2026-05-15', $result[0]->reportDate->format('Y-m-d'));
        // Item ke-10 = tanggal 06
        self::assertEquals('2026-05-06', $result[9]->reportDate->format('Y-m-d'));
    }

    function testFindLatestWithLimitSmallerThanData()
    {
        $report1 = new Report();
        $report1->reportDate = new DateTimeImmutable('2026-05-04');
        $report1->createdBy = null;

        $report2 = new Report();
        $report2->reportDate = new DateTimeImmutable('2026-05-05');
        $report2->createdBy = null;

        $report3 = new Report();
        $report3->reportDate = new DateTimeImmutable('2026-05-06');
        $report3->createdBy = null;

        $this->reportRepository->save($report1);
        $this->reportRepository->save($report2);
        $this->reportRepository->save($report3);

        $result = $this->reportRepository->findLatest(2);

        self::assertCount(2, $result);
        self::assertEquals('2026-05-06', $result[0]->reportDate->format('Y-m-d'));
        self::assertEquals('2026-05-05', $result[1]->reportDate->format('Y-m-d'));
    }

    function testCountByDateRange()
    {
        $report1 = new Report();
        $report1->reportDate = new DateTimeImmutable('2026-05-01');
        $report1->createdBy = null;

        $report2 = new Report();
        $report2->reportDate = new DateTimeImmutable('2026-05-05');
        $report2->createdBy = null;

        $report3 = new Report();
        $report3->reportDate = new DateTimeImmutable('2026-05-10');
        $report3->createdBy = null;

        $report4 = new Report();
        $report4->reportDate = new DateTimeImmutable('2026-05-15');
        $report4->createdBy = null;

        $this->reportRepository->save($report1);
        $this->reportRepository->save($report2);
        $this->reportRepository->save($report3);
        $this->reportRepository->save($report4);

        // Range 2026-05-01 s/d 2026-05-10 → 3 report
        $result = $this->reportRepository->countByDateRange(
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-10')
        );

        self::assertEquals(3, $result);
    }

    function testCountByDateRangeNoMatch()
    {
        $report = new Report();
        $report->reportDate = new DateTimeImmutable('2026-05-05');
        $report->createdBy = null;

        $this->reportRepository->save($report);

        // Range di luar data
        $result = $this->reportRepository->countByDateRange(
            new DateTimeImmutable('2026-06-01'),
            new DateTimeImmutable('2026-06-30')
        );

        self::assertEquals(0, $result);
    }

    function testFindByDateRangeLatest()
    {
        $report1 = new Report();
        $report1->reportDate = new DateTimeImmutable('2026-05-01');
        $report1->createdBy = null;

        $report2 = new Report();
        $report2->reportDate = new DateTimeImmutable('2026-05-05');
        $report2->createdBy = null;

        $report3 = new Report();
        $report3->reportDate = new DateTimeImmutable('2026-05-10');
        $report3->createdBy = null;

        $report4 = new Report();
        $report4->reportDate = new DateTimeImmutable('2026-05-15');
        $report4->createdBy = null;

        $this->reportRepository->save($report1);
        $this->reportRepository->save($report2);
        $this->reportRepository->save($report3);
        $this->reportRepository->save($report4);

        $result = $this->reportRepository->findByDateRangeLatest(
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-10'),
            10
        );

        self::assertCount(3, $result);

        // Descending: 10, 05, 01
        self::assertEquals('2026-05-10', $result[0]->reportDate->format('Y-m-d'));
        self::assertEquals('2026-05-05', $result[1]->reportDate->format('Y-m-d'));
        self::assertEquals('2026-05-01', $result[2]->reportDate->format('Y-m-d'));
    }

    function testFindByDateRangeLatestRespectsLimit()
    {
        for ($i = 1; $i <= 15; $i++) {
            $report = new Report();
            $report->reportDate = new DateTimeImmutable(
                sprintf('2026-05-%02d', $i)
            );
            $report->createdBy = null;

            $this->reportRepository->save($report);
        }

        // Range: 1 s/d 15, limit 5
        $result = $this->reportRepository->findByDateRangeLatest(
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-15'),
            5
        );

        self::assertCount(5, $result);

        // Descending: 15, 14, 13, 12, 11
        self::assertEquals('2026-05-15', $result[0]->reportDate->format('Y-m-d'));
        self::assertEquals('2026-05-11', $result[4]->reportDate->format('Y-m-d'));
    }

    function testFindByDateRangeLatestEmpty()
    {
        $result = $this->reportRepository->findByDateRangeLatest(
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-10'),
            10
        );

        self::assertCount(0, $result);
    }

    function testFindByDateRangeLatestExcludesOutsideRange()
    {
        $inside = new Report();
        $inside->reportDate = new DateTimeImmutable('2026-05-05');
        $inside->createdBy = null;

        $outsideBefore = new Report();
        $outsideBefore->reportDate = new DateTimeImmutable('2026-04-30');
        $outsideBefore->createdBy = null;

        $outsideAfter = new Report();
        $outsideAfter->reportDate = new DateTimeImmutable('2026-05-20');
        $outsideAfter->createdBy = null;

        $this->reportRepository->save($inside);
        $this->reportRepository->save($outsideBefore);
        $this->reportRepository->save($outsideAfter);

        $result = $this->reportRepository->findByDateRangeLatest(
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-10'),
            10
        );

        self::assertCount(1, $result);
        self::assertEquals('2026-05-05', $result[0]->reportDate->format('Y-m-d'));
    }

    function testFindByDateRangeLatestInclusiveBoundaries()
    {
        $onStart = new Report();
        $onStart->reportDate = new DateTimeImmutable('2026-05-01');
        $onStart->createdBy = null;

        $onEnd = new Report();
        $onEnd->reportDate = new DateTimeImmutable('2026-05-10');
        $onEnd->createdBy = null;

        $this->reportRepository->save($onStart);
        $this->reportRepository->save($onEnd);

        $result = $this->reportRepository->findByDateRangeLatest(
            new DateTimeImmutable('2026-05-01'),
            new DateTimeImmutable('2026-05-10'),
            10
        );

        // Kedua tanggal boundary harus include (BETWEEN inclusive)
        self::assertCount(2, $result);
    }
}