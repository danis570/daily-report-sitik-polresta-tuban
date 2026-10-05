<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use DateTimeImmutable;
use Exception;
use PDO;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Config\HolidayProvider;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Report;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\GenerateReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\GenerateReportResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;

class ReportGeneratorServiceTest extends TestCase
{
    private ReportGeneratorService $service;
    private ReportRepository $reportRepository;
    private ReportItemRepository $reportItemRepository;
    private ReportOptionRepository $reportOptionRepository;
    private HolidayProvider $holidayProvider;
    private PDO $pdo;

    /**
     * Template report ID yang dipakai generator.
     * Senin=66, Selasa=67, Rabu=68, Kamis=69, Jumat=70.
     */
    private const TEMPLATE_IDS = [
        66 => 'Senin',
        67 => 'Selasa',
        68 => 'Rabu',
        69 => 'Kamis',
        70 => 'Jumat',
    ];

   protected function setUp(): void
{
    Database::clearConnection();

    $this->pdo = Database::getConnection('dev');

    $this->reportRepository = new ReportRepository($this->pdo);
    $this->reportItemRepository = new ReportItemRepository($this->pdo);
    $this->reportOptionRepository = new ReportOptionRepository($this->pdo);
    $this->holidayProvider = new HolidayProvider();

    $this->service = new ReportGeneratorService(
        $this->reportRepository,
        $this->reportItemRepository,
        $this->holidayProvider
    );

    // Bersihkan semua data — urutan: anak dulu, parent terakhir
    $this->reportItemRepository->deleteAll();   // child of reports & report_options
    $this->reportRepository->deleteAll();       // child of users
    $this->reportOptionRepository->deleteAll(); // parent of report_items
    $this->pdo->exec("DELETE FROM users");      // ✅ WAJIB: parent of reports

    // Seed user ID 1 (untuk created_by)
    $this->seedUser();

    // Seed template 66-70
    $this->seedTemplates();
}

/**
 * Seed 1 user (ID eksplisit) supaya created_by bisa dipakai di test.
 */
private function seedUser(): void
{
    $stmt = $this->pdo->prepare("
        INSERT INTO users (id, email, password, role, created_at, updated_at)
        VALUES (?, ?, ?, 'user', NOW(), NOW())
    ");
    $stmt->execute([
        1,
        'test-user-' . uniqid() . '@gmail.com',
        password_hash('password123', PASSWORD_BCRYPT),
    ]);
}

    /**
     * Seed template report 66-70 + 1 item tiap template.
     *
     * Karena di-setUp() kita sudah menghapus semua data,
     * ID 66-70 kini kosong, jadi bisa kita insert eksplisit.
     */
    private function seedTemplates(): void
    {
        // 1. Buat 1 option — dipakai untuk semua FK di report_items
        $stmt = $this->pdo->prepare("
        INSERT INTO report_options (category, name, description, created_at, updated_at)
        VALUES ('activity', ?, NULL, NOW(), NOW())
    ");
        $stmt->execute(['Seed Option ' . uniqid()]);
        $optionId = (int) $this->pdo->lastInsertId();

        // 2. Buat report 66-70 + 1 item tiap report.
        //    report_date dummy unik per template (biar tidak kena uq_reports_date).
        $dummyDates = [
            66 => '2000-01-01',
            67 => '2000-01-02',
            68 => '2000-01-03',
            69 => '2000-01-04',
            70 => '2000-01-05',
        ];

        foreach (self::TEMPLATE_IDS as $id => $dayName) {

            // 2a. Insert report dengan ID eksplisit + tanggal dummy unik
            $stmt = $this->pdo->prepare("
            INSERT INTO reports (id, report_date, created_by, created_at, updated_at)
            VALUES (?, ?, NULL, NOW(), NOW())
        ");
            $stmt->execute([$id, $dummyDates[$id]]);

            // 2b. Insert 1 item ke report template
            $stmt = $this->pdo->prepare("
            INSERT INTO report_items (
                report_id, item_no,
                target_option_id, activity_option_id,
                personnel_strength_option_id, location_option_id,
                person_in_charge_option_id, expected_result_option_id,
                remarks, created_at, updated_at
            ) VALUES (?, 1, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
            $stmt->execute([
                $id,
                $optionId,
                $optionId,
                $optionId,
                $optionId,
                $optionId,
                $optionId,
                'Template ' . $dayName,
            ]);
        }
    }

    // ============================================================
    // VALIDASI
    // ============================================================

    public function testGenerateFailsWhenCreatedByNull(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-01';
        $request->endDate = '2026-10-07';
        $request->createdBy = null;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Identitas pembuat laporan tidak valid.');

        $this->service->generate($request);
    }

    public function testGenerateFailsWhenStartDateEmpty(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '';
        $request->endDate = '2026-10-07';
        $request->createdBy = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Tanggal mulai tidak boleh kosong.');

        $this->service->generate($request);
    }

    public function testGenerateFailsWhenEndDateEmpty(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-01';
        $request->endDate = '';
        $request->createdBy = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Tanggal akhir tidak boleh kosong.');

        $this->service->generate($request);
    }

    public function testGenerateFailsWhenDateInvalid(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = 'bukan-tanggal';
        $request->endDate = '2026-10-07';
        $request->createdBy = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Format tanggal tidak valid.');

        $this->service->generate($request);
    }

    public function testGenerateFailsWhenEndBeforeStart(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-10';
        $request->endDate = '2026-10-01';
        $request->createdBy = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.');

        $this->service->generate($request);
    }

    public function testGenerateFailsWhenRangeExceeds31Days(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-01';
        $request->endDate = '2026-11-15'; // 46 hari
        $request->createdBy = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Rentang tanggal maksimal 31 hari');

        $this->service->generate($request);
    }

    public function testGenerateAcceptsExactly31Days(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-01';
        $request->endDate = '2026-10-31'; // 31 hari
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        self::assertInstanceOf(GenerateReportResponse::class, $response);
    }

    // ============================================================
    // SKIP: WEEKEND
    // ============================================================

    public function testGenerateSkipsWeekend(): void
    {
        // 2026-10-03 = Sabtu, 2026-10-04 = Minggu
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-02'; // Jumat
        $request->endDate = '2026-10-05';   // Senin
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        // Generated: Jumat 02, Senin 05 → 2
        self::assertSame(2, $response->generated);
        self::assertContains('2026-10-02', $response->generatedDates);
        self::assertContains('2026-10-05', $response->generatedDates);

        // Skipped: Sabtu 03, Minggu 04 → 2
        self::assertSame(2, $response->skipped);

        $skippedDates = array_column($response->skippedDetails, 'date');
        self::assertContains('2026-10-03', $skippedDates);
        self::assertContains('2026-10-04', $skippedDates);
    }

    // ============================================================
    // SKIP: TANGGAL MERAH NASIONAL
    // ============================================================

    public function testGenerateSkipsNationalHoliday(): void
    {
        // 2026-08-17 = Senin, Kemerdekaan RI
        $request = new GenerateReportRequest();
        $request->startDate = '2026-08-17';
        $request->endDate = '2026-08-18'; // Selasa
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        // Generated: Selasa 18 → 1
        self::assertSame(1, $response->generated);
        self::assertContains('2026-08-18', $response->generatedDates);

        // Skipped: Senin 17 (tanggal merah) → 1
        self::assertSame(1, $response->skipped);
        self::assertSame('2026-08-17', $response->skippedDetails[0]['date']);
        self::assertSame('national_holiday', $response->skippedDetails[0]['reason']);
    }

    public function testGenerateSkipsNewYear(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-01-01'; // Kamis, Tahun Baru
        $request->endDate = '2026-01-02';   // Jumat
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        self::assertSame(1, $response->generated);
        self::assertContains('2026-01-02', $response->generatedDates);
        self::assertSame(1, $response->skipped);
        self::assertSame('2026-01-01', $response->skippedDetails[0]['date']);
    }

    public function testGenerateSkipsChristmas(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-12-24'; // Kamis
        $request->endDate = '2026-12-25';   // Jumat, Natal
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        self::assertSame(1, $response->generated);
        self::assertContains('2026-12-24', $response->generatedDates);
        self::assertSame(1, $response->skipped);
        self::assertSame('2026-12-25', $response->skippedDetails[0]['date']);
    }

    // ============================================================
    // SKIP: TANGGAL MERAH MANUAL
    // ============================================================

    public function testGenerateSkipsManualHoliday(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05'; // Senin
        $request->endDate = '2026-10-07';   // Rabu
        $request->createdBy = 1;
        $request->manualHolidays = ['2026-10-06']; // Selasa, cuti bersama

        $response = $this->service->generate($request);

        self::assertSame(2, $response->generated);
        self::assertContains('2026-10-05', $response->generatedDates);
        self::assertContains('2026-10-07', $response->generatedDates);

        self::assertSame(1, $response->skipped);
        self::assertSame('2026-10-06', $response->skippedDetails[0]['date']);
        self::assertStringContainsString('manual', $response->skippedDetails[0]['reason']);
    }

    // ============================================================
    // SKIP: DUPLIKAT
    // ============================================================

    public function testGenerateSkipsExistingDate(): void
    {
        // Pre-create report di 2026-10-06 (Selasa)
        $existing = new Report();
        $existing->reportDate = new DateTimeImmutable('2026-10-06');
        $existing->createdBy = null;
        $this->reportRepository->save($existing);

        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05'; // Senin
        $request->endDate = '2026-10-07';   // Rabu
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        // Generated: Senin 05, Rabu 07 → 2
        self::assertSame(2, $response->generated);
        self::assertContains('2026-10-05', $response->generatedDates);
        self::assertContains('2026-10-07', $response->generatedDates);

        // Skipped: Selasa 06 (sudah ada) → 1
        self::assertSame(1, $response->skipped);
        self::assertSame('2026-10-06', $response->skippedDetails[0]['date']);
        self::assertSame('exists', $response->skippedDetails[0]['reason']);
    }

    // ============================================================
    // HAPPY PATH
    // ============================================================

    public function testGenerateOneWeekSuccess(): void
    {
        // 2026-10-05 (Senin) s/d 2026-10-09 (Jumat) = 5 hari kerja
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05';
        $request->endDate = '2026-10-09';
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        self::assertSame(5, $response->generated);
        self::assertSame(0, $response->skipped);
        self::assertCount(5, $response->generatedDates);

        // Cek report benar-benar ada di DB
        self::assertSame(5, $this->reportRepository->countByDateRange(
            new DateTimeImmutable('2026-10-05'),
            new DateTimeImmutable('2026-10-09')
        ));
    }

    public function testGenerateIncludesBoundaryDates(): void
    {
        // 2026-10-05 (Senin) s/d 2026-10-05 (Senin) = 1 hari
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05';
        $request->endDate = '2026-10-05';
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        self::assertSame(1, $response->generated);
        self::assertContains('2026-10-05', $response->generatedDates);
    }

    public function testGenerateAllWeekdays(): void
    {
        // 2026-10-05 (Senin) s/d 2026-10-11 (Minggu) = 5 hari kerja, 2 skip
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05';
        $request->endDate = '2026-10-11';
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        self::assertSame(5, $response->generated);
        self::assertSame(2, $response->skipped);
    }

    // ============================================================
    // created_at = report_date - 2 hari, jam 08:00
    // ============================================================

    public function testGenerateSetsCreatedAtTwoDaysBefore(): void
    {
        // Report 2026-10-05 (Senin) → created_at 2026-10-03 08:00:00 (Sabtu)
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05';
        $request->endDate = '2026-10-05';
        $request->createdBy = 1;

        $this->service->generate($request);

        $report = $this->reportRepository->findByDate(
            new DateTimeImmutable('2026-10-05')
        );

        self::assertNotNull($report);
        self::assertSame(
            '2026-10-03 08:00:00',
            $report->createdAt->format('Y-m-d H:i:s')
        );
    }

    public function testGenerateSetsCreatedByFromRequest(): void
{
    $request = new GenerateReportRequest();
    $request->startDate = '2026-10-05';
    $request->endDate = '2026-10-05';
    $request->createdBy = 1; // ← ganti dari 99 ke 1

    $this->service->generate($request);

    $report = $this->reportRepository->findByDate(
        new DateTimeImmutable('2026-10-05')
    );

    self::assertSame(1, $report->createdBy);
}

    // ============================================================
    // TEMPLATE MAPPING
    // ============================================================

    public function testGenerateCopiesItemsFromCorrectTemplate(): void
    {
        // 2026-10-05 (Senin) → harus copy item dari template 66
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05';
        $request->endDate = '2026-10-05';
        $request->createdBy = 1;

        $this->service->generate($request);

        $report = $this->reportRepository->findByDate(
            new DateTimeImmutable('2026-10-05')
        );

        $items = $this->reportItemRepository->findByReportId($report->id);

        self::assertCount(1, $items);
        self::assertSame('Template Senin', $items[0]->remarks);
    }

    public function testGenerateCopiesItemsFromEachDayTemplate(): void
    {
        // 2026-10-05 (Senin) s/d 2026-10-09 (Jumat)
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05';
        $request->endDate = '2026-10-09';
        $request->createdBy = 1;

        $this->service->generate($request);

        $expectedRemarks = [
            '2026-10-05' => 'Template Senin',
            '2026-10-06' => 'Template Selasa',
            '2026-10-07' => 'Template Rabu',
            '2026-10-08' => 'Template Kamis',
            '2026-10-09' => 'Template Jumat',
        ];

        foreach ($expectedRemarks as $dateStr => $expectedRemark) {
            $report = $this->reportRepository->findByDate(
                new DateTimeImmutable($dateStr)
            );

            self::assertNotNull($report, "Report $dateStr tidak ditemukan.");

            $items = $this->reportItemRepository->findByReportId($report->id);

            self::assertCount(1, $items, "Item untuk $dateStr tidak sesuai.");
            self::assertSame(
                $expectedRemark,
                $items[0]->remarks,
                "Remark untuk $dateStr salah."
            );
        }
    }

    // ============================================================
    // SKIP: SEMUA DI-SKIP
    // ============================================================

    public function testGenerateAllSkipped(): void
    {
        // 2026-10-03 (Sabtu) s/d 2026-10-04 (Minggu) → semua skip
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-03';
        $request->endDate = '2026-10-04';
        $request->createdBy = 1;

        $response = $this->service->generate($request);

        self::assertSame(0, $response->generated);
        self::assertSame(2, $response->skipped);
        self::assertEmpty($response->generatedDates);
    }

    // ============================================================
    // IDEMPOTENT
    // ============================================================

    public function testGenerateTwiceIsIdempotent(): void
    {
        $request = new GenerateReportRequest();
        $request->startDate = '2026-10-05';
        $request->endDate = '2026-10-09';
        $request->createdBy = 1;

        $first = $this->service->generate($request);
        self::assertSame(5, $first->generated);
        self::assertSame(0, $first->skipped);

        // Generate ulang rentang sama → semua harus di-skip
        $second = $this->service->generate($request);
        self::assertSame(0, $second->generated);
        self::assertSame(5, $second->skipped);

        // Total report tetap 5
        self::assertSame(5, $this->reportRepository->countByDateRange(
            new DateTimeImmutable('2026-10-05'),
            new DateTimeImmutable('2026-10-09')
        ));
    }
}