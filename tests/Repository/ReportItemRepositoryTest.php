<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Report;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportItem;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;

class ReportItemRepositoryTest extends TestCase
{
    private ReportItemRepository $reportItemRepository;

    private ReportRepository $reportRepository;

    private ReportOptionRepository $reportOptionRepository;

    private ReportOption $targetOption;
    private ReportOption $activityOption;
    private ReportOption $personnelOption;
    private ReportOption $locationOption;
    private ReportOption $personInChargeOption;
    private ReportOption $expectedResultOption;

    protected function setUp(): void
    {
        Database::clearConnection();

        $pdo = Database::getConnection('dev');

        $this->reportItemRepository = new ReportItemRepository($pdo);
        $this->reportRepository = new ReportRepository($pdo);
        $this->reportOptionRepository = new ReportOptionRepository($pdo);

        $this->reportItemRepository->deleteAll();
        $this->reportRepository->deleteAll();
        $this->reportOptionRepository->deleteAll();

        $this->targetOption = $this->createReportOption(
            'target',
            'Pengarahan PJU Polres Tuban'
        );

        $this->activityOption = $this->createReportOption(
            'activity',
            'Apel pagi'
        );

        $this->personnelOption = $this->createReportOption(
            'personnel_strength',
            '4 anggota TIK'
        );

        $this->locationOption = $this->createReportOption(
            'location',
            'Lapangan Polres Tuban'
        );

        $this->personInChargeOption = $this->createReportOption(
            'person_in_charge',
            'Ps.KASI TIK'
        );

        $this->expectedResultOption = $this->createReportOption(
            'expected_result',
            'Terlaksanakannya apel pagi'
        );
    }

    private function createReport(): Report
    {
        $report = new Report();

        $report->reportDate = new DateTimeImmutable('2026-05-04');
        $report->createdBy = null;

        return $this->reportRepository->save($report);
    }

    private function createReportOption(
        string $category,
        string $name
    ): ReportOption {
        $option = new ReportOption();

        $option->category = $category;
        $option->name = $name;
        $option->description = null;

        return $this->reportOptionRepository->save($option);
    }

    private function createReportItem(
        int $reportId,
        int $itemNo = 1
    ): ReportItem {
        $reportItem = new ReportItem();

        $reportItem->reportId = $reportId;
        $reportItem->itemNo = $itemNo;

        $reportItem->targetOptionId = $this->targetOption->id;
        $reportItem->activityOptionId = $this->activityOption->id;
        $reportItem->personnelStrengthOptionId = $this->personnelOption->id;
        $reportItem->locationOptionId = $this->locationOption->id;
        $reportItem->personInChargeOptionId = $this->personInChargeOption->id;
        $reportItem->expectedResultOptionId = $this->expectedResultOption->id;

        $reportItem->remarks = 'Kegiatan berjalan dengan baik';

        return $reportItem;
    }

    public function testSaveSuccess(): void
    {
        $report = $this->createReport();

        $reportItem = $this->createReportItem($report->id);

        $result = $this->reportItemRepository->save($reportItem);

        self::assertNotNull($result->id);

        self::assertEquals(
            $report->id,
            $result->reportId
        );

        self::assertEquals(
            1,
            $result->itemNo
        );

        self::assertEquals(
            'Kegiatan berjalan dengan baik',
            $result->remarks
        );
    }

    public function testFindById(): void
    {
        $report = $this->createReport();

        $reportItem = $this->createReportItem($report->id);

        $saved = $this->reportItemRepository->save($reportItem);

        $found = $this->reportItemRepository
            ->findById($saved->id);

        self::assertNotNull($found);

        self::assertEquals(
            $saved->id,
            $found->id
        );

        self::assertEquals(
            $saved->reportId,
            $found->reportId
        );

        self::assertEquals(
            $saved->itemNo,
            $found->itemNo
        );

        self::assertEquals(
            $saved->targetOptionId,
            $found->targetOptionId
        );

        self::assertEquals(
            $saved->activityOptionId,
            $found->activityOptionId
        );

        self::assertEquals(
            $saved->personnelStrengthOptionId,
            $found->personnelStrengthOptionId
        );

        self::assertEquals(
            $saved->locationOptionId,
            $found->locationOptionId
        );

        self::assertEquals(
            $saved->personInChargeOptionId,
            $found->personInChargeOptionId
        );

        self::assertEquals(
            $saved->expectedResultOptionId,
            $found->expectedResultOptionId
        );

        self::assertEquals(
            $saved->remarks,
            $found->remarks
        );
    }

    public function testFindByIdNotFound(): void
    {
        $result = $this->reportItemRepository
            ->findById(999999);

        self::assertNull($result);
    }

    public function testFindByReportId(): void
    {
        $report = $this->createReport();

        $item1 = $this->createReportItem(
            $report->id,
            1
        );

        $item2 = $this->createReportItem(
            $report->id,
            2
        );

        $item3 = $this->createReportItem(
            $report->id,
            3
        );

        $this->reportItemRepository->save($item1);
        $this->reportItemRepository->save($item2);
        $this->reportItemRepository->save($item3);

        $results = $this->reportItemRepository
            ->findByReportId($report->id);

        self::assertCount(3, $results);

        self::assertEquals(
            1,
            $results[0]->itemNo
        );

        self::assertEquals(
            2,
            $results[1]->itemNo
        );

        self::assertEquals(
            3,
            $results[2]->itemNo
        );

        self::assertEquals(
            $report->id,
            $results[0]->reportId
        );
    }

    public function testFindByReportIdWhenNotFound(): void
    {
        $results = $this->reportItemRepository
            ->findByReportId(999999);

        self::assertIsArray($results);

        self::assertCount(0, $results);
    }

    public function testCountByReportId(): void
    {
        $report = $this->createReport();

        $item1 = $this->createReportItem(
            $report->id,
            1
        );

        $item2 = $this->createReportItem(
            $report->id,
            2
        );

        $this->reportItemRepository->save($item1);
        $this->reportItemRepository->save($item2);

        $result = $this->reportItemRepository
            ->countByReportId($report->id);

        self::assertEquals(2, $result);
    }

    public function testUpdate(): void
    {
        $report = $this->createReport();

        $reportItem = $this->createReportItem(
            $report->id,
            1
        );

        $saved = $this->reportItemRepository
            ->save($reportItem);

        $newActivity = $this->createReportOption(
            'activity',
            'Vidcon'
        );

        $saved->activityOptionId = $newActivity->id;

        $saved->remarks = 'Jaringan dan perangkat sudah dicek';

        $result = $this->reportItemRepository
            ->update($saved);

        self::assertTrue($result);

        $found = $this->reportItemRepository
            ->findById($saved->id);

        self::assertNotNull($found);

        self::assertEquals(
            $newActivity->id,
            $found->activityOptionId
        );

        self::assertEquals(
            'Jaringan dan perangkat sudah dicek',
            $found->remarks
        );
    }

    public function testDeleteById(): void
    {
        $report = $this->createReport();

        $reportItem = $this->createReportItem(
            $report->id
        );

        $saved = $this->reportItemRepository
            ->save($reportItem);

        $result = $this->reportItemRepository
            ->deleteById($saved->id);

        self::assertTrue($result);

        $found = $this->reportItemRepository
            ->findById($saved->id);

        self::assertNull($found);
    }

    public function testDeleteByIdNotFound(): void
    {
        $result = $this->reportItemRepository
            ->deleteById(999999);

        self::assertFalse($result);
    }

    public function testDeleteAll(): void
    {
        $report = $this->createReport();

        $item1 = $this->createReportItem(
            $report->id,
            1
        );

        $item2 = $this->createReportItem(
            $report->id,
            2
        );

        $this->reportItemRepository->save($item1);
        $this->reportItemRepository->save($item2);

        self::assertEquals(
            2,
            $this->reportItemRepository
                ->countByReportId($report->id)
        );

        $this->reportItemRepository->deleteAll();

        self::assertEquals(
            0,
            $this->reportItemRepository
                ->countByReportId($report->id)
        );
    }
}