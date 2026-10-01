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
}