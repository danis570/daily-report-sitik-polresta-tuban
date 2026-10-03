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

    private function createReport(
        string $date = '2026-05-04'
    ): Report {
        $report = new Report();

        $report->reportDate = new DateTimeImmutable($date);
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

    public function testCountOptionByDateRange(): void
    {
        $report1 = $this->createReport('2026-10-01');
        $report2 = $this->createReport('2026-10-05');

        $item1 = $this->createReportItem($report1->id);
        $item2 = $this->createReportItem($report2->id);

        $this->reportItemRepository->save($item1);
        $this->reportItemRepository->save($item2);

        $startDate = new DateTimeImmutable('2026-10-01');
        $endDate = new DateTimeImmutable('2026-10-09');

        $total = $this->reportItemRepository
            ->countOptionByDateRange(
                'activity',
                $this->activityOption->id,
                $startDate,
                $endDate
            );

        self::assertSame(2, $total);
    }

    // ============================================================
// TEST: countUsageByOptionId()
// ============================================================

    public function testCountUsageByOptionIdWhenNotUsed(): void
    {
        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->targetOption->id);

        self::assertSame(0, $result);
    }

    public function testCountUsageByOptionIdForTarget(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->targetOption->id);

        self::assertSame(1, $result);
    }

    public function testCountUsageByOptionIdForActivity(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->activityOption->id);

        self::assertSame(1, $result);
    }

    public function testCountUsageByOptionIdForPersonnel(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->personnelOption->id);

        self::assertSame(1, $result);
    }

    public function testCountUsageByOptionIdForLocation(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->locationOption->id);

        self::assertSame(1, $result);
    }

    public function testCountUsageByOptionIdForPersonInCharge(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->personInChargeOption->id);

        self::assertSame(1, $result);
    }

    public function testCountUsageByOptionIdForExpectedResult(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->expectedResultOption->id);

        self::assertSame(1, $result);
    }

    public function testCountUsageByOptionIdMultipleUsages(): void
    {
        $report1 = $this->createReport('2026-10-01');
        $report2 = $this->createReport('2026-10-02');
        $report3 = $this->createReport('2026-10-03');

        $item1 = $this->createReportItem($report1->id, 1);
        $item2 = $this->createReportItem($report2->id, 1);
        $item3 = $this->createReportItem($report3->id, 1);

        $this->reportItemRepository->save($item1);
        $this->reportItemRepository->save($item2);
        $this->reportItemRepository->save($item3);

        // Opsi activity dipakai 3× (di 3 report berbeda)
        $result = $this->reportItemRepository
            ->countUsageByOptionId($this->activityOption->id);

        self::assertSame(3, $result);
    }

    public function testCountUsageByOptionIdIgnoresOtherOptions(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        // Buat opsi lain yang TIDAK dipakai
        $unusedOption = $this->createReportOption(
            'activity',
            'Opsi Tidak Dipakai'
        );

        // Opsi yang dipakai → 1
        self::assertSame(
            1,
            $this->reportItemRepository
                ->countUsageByOptionId($this->activityOption->id)
        );

        // Opsi yang tidak dipakai → 0
        self::assertSame(
            0,
            $this->reportItemRepository
                ->countUsageByOptionId($unusedOption->id)
        );
    }

    public function testCountUsageByOptionIdNonExistentOption(): void
    {
        $report = $this->createReport();
        $item = $this->createReportItem($report->id);

        $this->reportItemRepository->save($item);

        // Opsi dengan ID yang tidak ada → 0
        $result = $this->reportItemRepository
            ->countUsageByOptionId(999999);

        self::assertSame(0, $result);
    }


    // ============================================================
// TEST: findUsageDetailsByOptionId()
// ============================================================

    public function testFindUsageDetailsByOptionIdWhenNotUsed(): void
    {
        $result = $this->reportItemRepository
            ->findUsageDetailsByOptionId($this->targetOption->id);

        self::assertIsArray($result);
        self::assertCount(0, $result);
    }

    public function testFindUsageDetailsByOptionIdSingleUsage(): void
    {
        $report = $this->createReport('2026-10-05');
        $item = $this->createReportItem($report->id, 1);

        $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->findUsageDetailsByOptionId($this->activityOption->id);

        self::assertCount(1, $result);

        self::assertSame(
            $report->id,
            (int) $result[0]['report_id']
        );

        self::assertSame(
            1,
            (int) $result[0]['item_no']
        );

        self::assertSame(
            '2026-10-05',
            $result[0]['report_date']
        );
    }

    public function testFindUsageDetailsByOptionIdOrderedByDateDesc(): void
    {
        $report1 = $this->createReport('2026-10-01');
        $report2 = $this->createReport('2026-10-05');
        $report3 = $this->createReport('2026-10-03');

        $this->reportItemRepository->save(
            $this->createReportItem($report1->id, 1)
        );
        $this->reportItemRepository->save(
            $this->createReportItem($report2->id, 1)
        );
        $this->reportItemRepository->save(
            $this->createReportItem($report3->id, 1)
        );

        $result = $this->reportItemRepository
            ->findUsageDetailsByOptionId($this->activityOption->id);

        self::assertCount(3, $result);

        // Order DESC by report_date: 2026-10-05, 2026-10-03, 2026-10-01
        self::assertSame('2026-10-05', $result[0]['report_date']);
        self::assertSame('2026-10-03', $result[1]['report_date']);
        self::assertSame('2026-10-01', $result[2]['report_date']);
    }

    public function testFindUsageDetailsByOptionIdRespectsLimit(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $report = $this->createReport(
                sprintf('2026-10-%02d', $i)
            );

            $this->reportItemRepository->save(
                $this->createReportItem($report->id, 1)
            );
        }

        $result = $this->reportItemRepository
            ->findUsageDetailsByOptionId($this->activityOption->id, 5);

        self::assertCount(5, $result);
    }

    public function testFindUsageDetailsByOptionIdIncludesItemAndReportId(): void
    {
        $report = $this->createReport('2026-10-08');
        $item = $this->createReportItem($report->id, 3);

        $saved = $this->reportItemRepository->save($item);

        $result = $this->reportItemRepository
            ->findUsageDetailsByOptionId($this->activityOption->id);

        self::assertCount(1, $result);

        self::assertArrayHasKey('item_id', $result[0]);
        self::assertArrayHasKey('item_no', $result[0]);
        self::assertArrayHasKey('report_id', $result[0]);
        self::assertArrayHasKey('report_date', $result[0]);

        self::assertSame($saved->id, (int) $result[0]['item_id']);
        self::assertSame(3, (int) $result[0]['item_no']);
        self::assertSame($report->id, (int) $result[0]['report_id']);
        self::assertSame('2026-10-08', $result[0]['report_date']);
    }
}