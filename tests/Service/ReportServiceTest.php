<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class ReportServiceTest extends TestCase
{
    private ReportService $reportService;
    private ReportItemRepository $reportItemRepository;
    private ReportRepository $reportRepository;
    private UserService $userService;
    private UserRepository $userRepository;

    private ReportOptionRepository $reportOptionRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();

        $this->reportRepository = new ReportRepository($connection);
        $this->reportItemRepository = new ReportItemRepository($connection);
        $this->reportOptionRepository = new ReportOptionRepository($connection);   // ← TAMBAH

        $this->reportService = new ReportService(
            $this->reportRepository,
            $this->reportItemRepository
        );

        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        // Bersihkan
        $this->reportItemRepository->deleteAll();       // ← TAMBAH
        $this->reportOptionRepository->deleteAll();     // ← TAMBAH
        $this->reportRepository->deleteAll();
        $this->userRepository->deleteAll();

         $this->seedTemplates();
    }

    /**
 * Seed template report 66-70 + 1 item tiap template.
 * Dipakai supaya test proteksi hapus bisa jalan.
 */
private function seedTemplates(): void
{
    $pdo = Database::getConnection();

    // 1. Buat 1 option — dipakai untuk semua FK di report_items
    $stmt = $pdo->prepare("
        INSERT INTO report_options (category, name, description, created_at, updated_at)
        VALUES ('activity', ?, NULL, NOW(), NOW())
    ");
    $stmt->execute(['Seed Option ' . uniqid()]);
    $optionId = (int) $pdo->lastInsertId();

    // 2. Tanggal dummy unik per template (biar tidak kena uq_reports_date)
    $dummyDates = [
        66 => '2000-01-01',
        67 => '2000-01-02',
        68 => '2000-01-03',
        69 => '2000-01-04',
        70 => '2000-01-05',
    ];

    foreach ($dummyDates as $id => $date) {

        // Insert report dengan ID eksplisit
        $stmt = $pdo->prepare("
            INSERT INTO reports (id, report_date, created_by, created_at, updated_at)
            VALUES (?, ?, NULL, NOW(), NOW())
        ");
        $stmt->execute([$id, $date]);

        // Insert 1 item
        $stmt = $pdo->prepare("
            INSERT INTO report_items (
                report_id, item_no,
                target_option_id, activity_option_id,
                personnel_strength_option_id, location_option_id,
                person_in_charge_option_id, expected_result_option_id,
                remarks, created_at, updated_at
            ) VALUES (?, 1, ?, ?, ?, ?, ?, ?, 'Template', NOW(), NOW())
        ");
        $stmt->execute([$id, $optionId, $optionId, $optionId, $optionId, $optionId, $optionId]);
    }
}

    private function createFullItem(int $reportId, int $itemNo): void
    {
        $option = new ReportOption();
        $option->category = 'activity';
        $option->name = 'Test ' . $itemNo . ' ' . uniqid();
        $option->description = null;
        $saved = $this->reportOptionRepository->save($option);

        $item = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportItem();
        $item->reportId = $reportId;
        $item->itemNo = $itemNo;
        $item->targetOptionId = $saved->id;
        $item->activityOptionId = $saved->id;
        $item->personnelStrengthOptionId = $saved->id;
        $item->locationOptionId = $saved->id;
        $item->personInChargeOptionId = $saved->id;
        $item->expectedResultOptionId = $saved->id;
        $item->remarks = 'Remarks item ' . $itemNo;

        $this->reportItemRepository->save($item);
    }

    private function createReportForTest(string $date, ?int $createdBy = null): int
    {
        $request = new UserAddReportRequest();
        $request->reportDate = $date;
        $request->createdBy = $createdBy ?? $this->createUser('dup-' . uniqid() . '@gmail.com');

        $response = $this->reportService->create($request);

        return $response->report->id;
    }

    private function createUser(string $email): int
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email = $email;
        $userRequest->password = 'password123';

        $userResult = $this->userService->register($userRequest);

        return $userResult->user->id;
    }

    public function testCreateSuccess()
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'petugas@gmail.com';
        $userRequest->password = 'rahasia123';            // ✅ diperbaiki
        $userResult = $this->userService->register($userRequest);

        $userId = $userResult->user->id;

        $request = new UserAddReportRequest();
        $request->reportDate = '2026-10-01';
        $request->createdBy = $userId;

        $response = $this->reportService->create($request);

        self::assertInstanceOf(UserAddReportResponse::class, $response);
        self::assertNotNull($response->report->id);
        self::assertEquals('2026-10-01', $response->report->reportDate->format('Y-m-d'));
        self::assertEquals($userId, $response->report->createdBy);
    }

    public function testCreateDateEmpty()
    {
        $request = new UserAddReportRequest();
        $request->reportDate = '';
        $request->createdBy = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Tanggal laporan tidak boleh kosong.');

        $this->reportService->create($request);
    }

    public function testCreateCreatedByNull()
    {
        $request = new UserAddReportRequest();
        $request->reportDate = '2026-10-01';
        $request->createdBy = null;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Identitas pembuat laporan tidak valid.');

        $this->reportService->create($request);
    }

    public function testCreateInvalidDateFormat()
    {
        $request = new UserAddReportRequest();
        $request->reportDate = 'bukan-tanggal-valid';
        $request->createdBy = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Format tanggal laporan tidak valid.');

        $this->reportService->create($request);
    }

    public function testCreateDuplicateDate()
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'petugas2@gmail.com';
        $userRequest->password = 'rahasia123';            // ✅ diperbaiki
        $userResult = $this->userService->register($userRequest);
        $userId = $userResult->user->id;

        $request1 = new UserAddReportRequest();
        $request1->reportDate = '2026-10-01';
        $request1->createdBy = $userId;
        $this->reportService->create($request1);

        $request2 = new UserAddReportRequest();
        $request2->reportDate = '2026-10-01';
        $request2->createdBy = $userId;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Laporan untuk tanggal 01-10-2026 sudah pernah dibuat.');

        $this->reportService->create($request2);
    }

    public function testUpdateReportSuccess()
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'operator@gmail.com';
        $userRequest->password = 'password123';           // ✅ eksplisit
        $userResult = $this->userService->register($userRequest);

        $createRequest = new UserAddReportRequest();
        $createRequest->reportDate = '2026-10-10';
        $createRequest->createdBy = $userResult->user->id;
        $createResponse = $this->reportService->create($createRequest);

        $updateRequest = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportRequest();
        $updateRequest->id = $createResponse->report->id;
        $updateRequest->reportDate = '2026-10-11';
        $updateRequest->createdBy = $userResult->user->id;

        $updateResponse = $this->reportService->update($updateRequest);

        self::assertInstanceOf(\Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportResponse::class, $updateResponse);
        self::assertEquals('2026-10-11', $updateResponse->report->reportDate->format('Y-m-d'));
    }

    public function testDeleteReportSuccess()
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'operator2@gmail.com';
        $userRequest->password = 'password123';           // ✅ eksplisit
        $userResult = $this->userService->register($userRequest);

        $createRequest = new UserAddReportRequest();
        $createRequest->reportDate = '2026-10-12';
        $createRequest->createdBy = $userResult->user->id;
        $createResponse = $this->reportService->create($createRequest);

        $this->reportService->delete($createResponse->report->id);

        $check = $this->reportRepository->findById($createResponse->report->id);
        self::assertNull($check);
    }

    // ============================================================
    // TEST: duplicate()
    // ============================================================

    public function testDuplicateSuccess(): void
    {
        $userId = $this->createUser('dup-success@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);

        // Buat 3 item di source
        $this->createFullItem($sourceId, 1);
        $this->createFullItem($sourceId, 2);
        $this->createFullItem($sourceId, 3);

        // Duplikat ke tanggal baru
        $new = $this->reportService->duplicate($sourceId, '2026-10-15');

        self::assertNotNull($new->id);
        self::assertNotEquals($sourceId, $new->id);
        self::assertEquals('2026-10-15', $new->reportDate->format('Y-m-d'));

        // Cek 3 item tersalin
        $newItems = $this->reportItemRepository->findByReportId($new->id);
        self::assertCount(3, $newItems);
    }

    public function testDuplicateWithCreatedBy(): void
    {
        // Buat user asli untuk dijadikan createdBy
        $userId = $this->createUser('dup-createdby-target@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);

        // Duplikat dengan createdBy = user yang baru dibuat
        $new = $this->reportService->duplicate($sourceId, '2026-10-15', $userId);

        self::assertEquals($userId, $new->createdBy);
    }

    public function testDuplicateCreatedByFallbackToSource(): void
    {
        $userId = $this->createUser('dup-fallback@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);

        // Duplikat tanpa createdBy (null) → harus fallback ke createdBy source
        $new = $this->reportService->duplicate($sourceId, '2026-10-15');

        self::assertEquals($userId, $new->createdBy);
    }

    public function testDuplicateEmptySourceReport(): void
    {
        $userId = $this->createUser('dup-empty@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);
        // Tidak ada item

        $new = $this->reportService->duplicate($sourceId, '2026-10-15');

        self::assertNotNull($new->id);

        $newItems = $this->reportItemRepository->findByReportId($new->id);
        self::assertCount(0, $newItems);
    }

    public function testDuplicatePreservesAllFields(): void
    {
        $userId = $this->createUser('dup-preserve@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);

        // Buat item dengan itemNo = 5
        $this->createFullItem($sourceId, 5);

        $sourceItems = $this->reportItemRepository->findByReportId($sourceId);
        $originalItem = $sourceItems[0];

        // Duplikat
        $new = $this->reportService->duplicate($sourceId, '2026-10-15');

        $newItems = $this->reportItemRepository->findByReportId($new->id);
        self::assertCount(1, $newItems);

        $copied = $newItems[0];

        self::assertEquals($new->id, $copied->reportId);
        self::assertEquals($originalItem->itemNo, $copied->itemNo);
        self::assertEquals($originalItem->targetOptionId, $copied->targetOptionId);
        self::assertEquals($originalItem->activityOptionId, $copied->activityOptionId);
        self::assertEquals($originalItem->personnelStrengthOptionId, $copied->personnelStrengthOptionId);
        self::assertEquals($originalItem->locationOptionId, $copied->locationOptionId);
        self::assertEquals($originalItem->personInChargeOptionId, $copied->personInChargeOptionId);
        self::assertEquals($originalItem->expectedResultOptionId, $copied->expectedResultOptionId);
        self::assertEquals($originalItem->remarks, $copied->remarks);
    }

    public function testDuplicateDoesNotAffectSource(): void
    {
        $userId = $this->createUser('dup-source-safe@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);

        $this->createFullItem($sourceId, 1);
        $this->createFullItem($sourceId, 2);

        // Duplikat
        $this->reportService->duplicate($sourceId, '2026-10-15');

        // Source tetap punya 2 item
        $sourceItems = $this->reportItemRepository->findByReportId($sourceId);
        self::assertCount(2, $sourceItems);

        // Source tanggal tidak berubah
        $sourceReloaded = $this->reportRepository->findById($sourceId);
        self::assertEquals('2026-10-01', $sourceReloaded->reportDate->format('Y-m-d'));
    }

    public function testDuplicateFailsWhenSourceNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Laporan sumber tidak ditemukan');

        $this->reportService->duplicate(999999, '2026-10-15');
    }

    public function testDuplicateFailsWhenTargetDateInvalid(): void
    {
        $userId = $this->createUser('dup-invalid-date@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Format tanggal tidak valid');

        $this->reportService->duplicate($sourceId, 'bukan-tanggal');
    }

    public function testDuplicateFailsWhenTargetDateAlreadyExists(): void
    {
        $userId = $this->createUser('dup-date-exists@gmail.com');
        $sourceId = $this->createReportForTest('2026-10-01', $userId);

        // Buat report di tanggal target
        $this->createReportForTest('2026-10-15', $userId);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah dipakai');

        $this->reportService->duplicate($sourceId, '2026-10-15');
    }

    // ============================================================
// TEST: delete() — proteksi template
// ============================================================

    public function testDeleteFailsWhenReportIsProtectedTemplate(): void
    {
        // Template 66 tidak boleh dihapus
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('template acuan');

        $this->reportService->delete(66);
    }

    public function testDeleteFailsForAllProtectedTemplates(): void
    {
        foreach ([66, 67, 68, 69, 70] as $templateId) {
            try {
                $this->reportService->delete($templateId);
                self::fail("Report #{$templateId} seharusnya throw Exception.");
            } catch (Exception $e) {
                self::assertStringContainsString(
                    'template acuan',
                    $e->getMessage(),
                    "Pesan error untuk #{$templateId} salah."
                );
            }
        }
    }

    public function testDeleteSucceedsForNonProtectedReport(): void
    {
        // Buat report biasa
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'delete-non-template@gmail.com';
        $userRequest->password = 'password123';
        $userResult = $this->userService->register($userRequest);

        $createRequest = new UserAddReportRequest();
        $createRequest->reportDate = '2026-10-20';
        $createRequest->createdBy = $userResult->user->id;
        $createResponse = $this->reportService->create($createRequest);

        $reportId = $createResponse->report->id;

        // Pastikan bukan di daftar template
        self::assertNotContains($reportId, [66, 67, 68, 69, 70]);

        // Hapus → harus sukses
        $this->reportService->delete($reportId);

        $check = $this->reportRepository->findById($reportId);
        self::assertNull($check);
    }
}