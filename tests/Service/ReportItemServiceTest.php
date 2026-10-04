<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportItemRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportItemResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportItemRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class ReportItemServiceTest extends TestCase
{
    private ReportItemService $reportItemService;
    private ReportItemRepository $reportItemRepository;
    private ReportRepository $reportRepository;
    private ReportOptionRepository $reportOptionRepository;
    private UserService $userService;
    private UserRepository $userRepository;
    private ReportService $reportService;

    protected function setUp(): void
    {
        $connection = Database::getConnection();

        // Inisialisasi repository
        $this->reportItemRepository = new ReportItemRepository($connection);
        $this->reportRepository = new ReportRepository($connection);
        $this->reportOptionRepository = new ReportOptionRepository($connection);
        $this->userRepository = new UserRepository($connection);

        // Service
        $this->userService = new UserService($this->userRepository);
        $this->reportService = new ReportService(
            $this->reportRepository,
            $this->reportItemRepository
        );
        $this->reportItemService = new ReportItemService(
            $this->reportItemRepository,
            $this->reportRepository
        );

        // Bersihkan data (urutan: anak dulu, lalu parent)
        $this->reportItemRepository->deleteAll();
        $this->reportOptionRepository->deleteAll();
        $this->reportRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    /* =============================================================
     * HELPER
     * ============================================================= */

    private function createDummyOption(string $category, string $name): ReportOption
    {
        $option = new ReportOption();
        $option->category = $category;
        $option->name = $name;

        return $this->reportOptionRepository->save($option);
    }

    private function createUser(string $email): int
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email = $email;
        $userRequest->password = 'password123';

        $userResult = $this->userService->register($userRequest);

        return $userResult->user->id;
    }

    private function createReport(string $date, int $userId): int
    {
        $reportRequest = new UserAddReportRequest();
        $reportRequest->reportDate = $date;
        $reportRequest->createdBy = $userId;

        $reportResponse = $this->reportService->create($reportRequest);

        return $reportResponse->report->id;
    }

    /* =============================================================
     * CREATE TESTS
     * ============================================================= */

    public function testCreateSuccessWithManualItemNo(): void
    {
        // Setup: user + report + options
        $userId = $this->createUser('create-manual@gmail.com');
        $reportId = $this->createReport('2026-10-01', $userId);

        $opt1 = $this->createDummyOption('Target', 'Sasaran 1');
        $opt2 = $this->createDummyOption('Activity', 'Patroli');
        $opt3 = $this->createDummyOption('Personnel', '10 Personel');
        $opt4 = $this->createDummyOption('Location', 'Tuban Kota');
        $opt5 = $this->createDummyOption('PIC', 'Kanit Patroli');
        $opt6 = $this->createDummyOption('Result', 'Aman Terkendali');

        // Create item dengan itemNo manual = 1
        $request = new UserAddReportItemRequest();
        $request->reportId = $reportId;
        $request->itemNo = 1;   // ← manual
        $request->targetOptionId = $opt1->id;
        $request->activityOptionId = $opt2->id;
        $request->personnelStrengthOptionId = $opt3->id;
        $request->locationOptionId = $opt4->id;
        $request->personInChargeOptionId = $opt5->id;
        $request->expectedResultOptionId = $opt6->id;
        $request->remarks = 'Melaksanakan patroli siber di wilayah Polres Tuban.';

        $response = $this->reportItemService->create($request);

        self::assertInstanceOf(UserAddReportItemResponse::class, $response);
        self::assertEquals(1, $response->reportItem->itemNo);
        self::assertEquals($reportId, $response->reportItem->reportId);
    }

    public function testCreateSuccessWithCustomItemNo(): void
    {
        $userId = $this->createUser('create-custom@gmail.com');
        $reportId = $this->createReport('2026-10-02', $userId);

        $opt = $this->createDummyOption('Target', 'Sasaran Custom');

        $request = new UserAddReportItemRequest();
        $request->reportId = $reportId;
        $request->itemNo = 5;   // ← nomor custom
        $request->targetOptionId = $opt->id;
        $request->activityOptionId = $opt->id;
        $request->personnelStrengthOptionId = $opt->id;
        $request->locationOptionId = $opt->id;
        $request->personInChargeOptionId = $opt->id;
        $request->expectedResultOptionId = $opt->id;
        $request->remarks = 'Item dengan nomor custom.';

        $response = $this->reportItemService->create($request);

        self::assertEquals(5, $response->reportItem->itemNo);
    }

    public function testCreateFailsWhenItemNoEmpty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Nomor giat wajib diisi.');

        $userId = $this->createUser('create-null-itemno@gmail.com');
        $reportId = $this->createReport('2026-10-03', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        $request = new UserAddReportItemRequest();
        $request->reportId = $reportId;
        $request->itemNo = null;   // ← kosong
        $request->targetOptionId = $opt->id;
        $request->activityOptionId = $opt->id;
        $request->personnelStrengthOptionId = $opt->id;
        $request->locationOptionId = $opt->id;
        $request->personInChargeOptionId = $opt->id;
        $request->expectedResultOptionId = $opt->id;

        $this->reportItemService->create($request);
    }

    public function testCreateFailsWhenItemNoDuplicate(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah dipakai');

        $userId = $this->createUser('create-dup-itemno@gmail.com');
        $reportId = $this->createReport('2026-10-04', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        // Item pertama dengan itemNo = 1
        $req1 = new UserAddReportItemRequest();
        $req1->reportId = $reportId;
        $req1->itemNo = 1;
        $req1->targetOptionId = $opt->id;
        $req1->activityOptionId = $opt->id;
        $req1->personnelStrengthOptionId = $opt->id;
        $req1->locationOptionId = $opt->id;
        $req1->personInChargeOptionId = $opt->id;
        $req1->expectedResultOptionId = $opt->id;
        $this->reportItemService->create($req1);

        // Item kedua dengan itemNo = 1 (duplikat)
        $req2 = new UserAddReportItemRequest();
        $req2->reportId = $reportId;
        $req2->itemNo = 1;   // ← duplikat
        $req2->targetOptionId = $opt->id;
        $req2->activityOptionId = $opt->id;
        $req2->personnelStrengthOptionId = $opt->id;
        $req2->locationOptionId = $opt->id;
        $req2->personInChargeOptionId = $opt->id;
        $req2->expectedResultOptionId = $opt->id;

        $this->reportItemService->create($req2);
    }

    public function testCreateReportNotFound(): void
    {
        $request = new UserAddReportItemRequest();
        $request->reportId = 9999;
        $request->itemNo = 1;
        $request->targetOptionId = 1;
        $request->activityOptionId = 2;
        $request->personnelStrengthOptionId = 3;
        $request->locationOptionId = 4;
        $request->personInChargeOptionId = 5;
        $request->expectedResultOptionId = 6;
        $request->remarks = 'Aktivitas dinas harian.';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Laporan utama tidak ditemukan.');

        $this->reportItemService->create($request);
    }

    /* =============================================================
     * UPDATE TESTS
     * ============================================================= */

    public function testUpdateSuccess(): void
    {
        $userId = $this->createUser('update-success@gmail.com');
        $reportId = $this->createReport('2026-10-05', $userId);

        $optTarget = $this->createDummyOption('Target', 'Target Awal');
        $optActivity1 = $this->createDummyOption('Activity', 'Patroli');
        $optActivity2 = $this->createDummyOption('Activity', 'Sambang Tokoh');
        $optPers = $this->createDummyOption('Personnel', '2 Personel');
        $optLoc = $this->createDummyOption('Location', 'Tuban');
        $optPic = $this->createDummyOption('PIC', 'Kasi');
        $optRes = $this->createDummyOption('Result', 'Kondusif');

        // Create item
        $requestCreate = new UserAddReportItemRequest();
        $requestCreate->reportId = $reportId;
        $requestCreate->itemNo = 1;   // ← manual
        $requestCreate->targetOptionId = $optTarget->id;
        $requestCreate->activityOptionId = $optActivity1->id;
        $requestCreate->personnelStrengthOptionId = $optPers->id;
        $requestCreate->locationOptionId = $optLoc->id;
        $requestCreate->personInChargeOptionId = $optPic->id;
        $requestCreate->expectedResultOptionId = $optRes->id;
        $requestCreate->remarks = 'Keterangan awal aktivitas dinas.';

        $responseCreate = $this->reportItemService->create($requestCreate);

        // Update item
        $requestUpdate = new UserUpdateReportItemRequest();
        $requestUpdate->id = $responseCreate->reportItem->id;
        $requestUpdate->itemNo = 1;   // ← manual
        $requestUpdate->targetOptionId = $optTarget->id;
        $requestUpdate->activityOptionId = $optActivity2->id;
        $requestUpdate->personnelStrengthOptionId = $optPers->id;
        $requestUpdate->locationOptionId = $optLoc->id;
        $requestUpdate->personInChargeOptionId = $optPic->id;
        $requestUpdate->expectedResultOptionId = $optRes->id;
        $requestUpdate->remarks = 'Keterangan setelah diperbarui.';

        $responseUpdate = $this->reportItemService->update($requestUpdate);

        self::assertEquals($optActivity2->id, $responseUpdate->reportItem->activityOptionId);
        self::assertEquals('Keterangan setelah diperbarui.', $responseUpdate->reportItem->remarks);
        self::assertEquals(1, $responseUpdate->reportItem->itemNo);
    }

    public function testUpdateItemNoToNewValue(): void
    {
        $userId = $this->createUser('update-itemno@gmail.com');
        $reportId = $this->createReport('2026-10-06', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        // Create item dengan itemNo = 1
        $requestCreate = new UserAddReportItemRequest();
        $requestCreate->reportId = $reportId;
        $requestCreate->itemNo = 1;
        $requestCreate->targetOptionId = $opt->id;
        $requestCreate->activityOptionId = $opt->id;
        $requestCreate->personnelStrengthOptionId = $opt->id;
        $requestCreate->locationOptionId = $opt->id;
        $requestCreate->personInChargeOptionId = $opt->id;
        $requestCreate->expectedResultOptionId = $opt->id;
        $requestCreate->remarks = 'Item awal.';

        $responseCreate = $this->reportItemService->create($requestCreate);

        // Update itemNo jadi 3
        $requestUpdate = new UserUpdateReportItemRequest();
        $requestUpdate->id = $responseCreate->reportItem->id;
        $requestUpdate->itemNo = 3;   // ← ubah nomor
        $requestUpdate->targetOptionId = $opt->id;
        $requestUpdate->activityOptionId = $opt->id;
        $requestUpdate->personnelStrengthOptionId = $opt->id;
        $requestUpdate->locationOptionId = $opt->id;
        $requestUpdate->personInChargeOptionId = $opt->id;
        $requestUpdate->expectedResultOptionId = $opt->id;
        $requestUpdate->remarks = 'Item setelah diubah nomor.';

        $responseUpdate = $this->reportItemService->update($requestUpdate);

        self::assertEquals(3, $responseUpdate->reportItem->itemNo);
    }

    public function testUpdateFailsWhenItemNoDuplicate(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah dipakai oleh item lain');

        $userId = $this->createUser('update-dup-itemno@gmail.com');
        $reportId = $this->createReport('2026-10-07', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        // Item 1 dengan itemNo = 1
        $req1 = new UserAddReportItemRequest();
        $req1->reportId = $reportId;
        $req1->itemNo = 1;
        $req1->targetOptionId = $opt->id;
        $req1->activityOptionId = $opt->id;
        $req1->personnelStrengthOptionId = $opt->id;
        $req1->locationOptionId = $opt->id;
        $req1->personInChargeOptionId = $opt->id;
        $req1->expectedResultOptionId = $opt->id;
        $this->reportItemService->create($req1);

        // Item 2 dengan itemNo = 2
        $req2 = new UserAddReportItemRequest();
        $req2->reportId = $reportId;
        $req2->itemNo = 2;
        $req2->targetOptionId = $opt->id;
        $req2->activityOptionId = $opt->id;
        $req2->personnelStrengthOptionId = $opt->id;
        $req2->locationOptionId = $opt->id;
        $req2->personInChargeOptionId = $opt->id;
        $req2->expectedResultOptionId = $opt->id;
        $created2 = $this->reportItemService->create($req2);

        // Update item 2 → coba pakai itemNo = 1 (sudah dipakai item 1)
        $reqUpdate = new UserUpdateReportItemRequest();
        $reqUpdate->id = $created2->reportItem->id;
        $reqUpdate->itemNo = 1;   // ← duplikat
        $reqUpdate->targetOptionId = $opt->id;
        $reqUpdate->activityOptionId = $opt->id;
        $reqUpdate->personnelStrengthOptionId = $opt->id;
        $reqUpdate->locationOptionId = $opt->id;
        $reqUpdate->personInChargeOptionId = $opt->id;
        $reqUpdate->expectedResultOptionId = $opt->id;
        $reqUpdate->remarks = 'Coba duplikat nomor.';

        $this->reportItemService->update($reqUpdate);
    }

    public function testUpdateAllowsSameItemNoForItself(): void
    {
        // Update item tanpa mengubah itemNo — harus berhasil
        $userId = $this->createUser('update-same-itemno@gmail.com');
        $reportId = $this->createReport('2026-10-08', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        $reqCreate = new UserAddReportItemRequest();
        $reqCreate->reportId = $reportId;
        $reqCreate->itemNo = 1;
        $reqCreate->targetOptionId = $opt->id;
        $reqCreate->activityOptionId = $opt->id;
        $reqCreate->personnelStrengthOptionId = $opt->id;
        $reqCreate->locationOptionId = $opt->id;
        $reqCreate->personInChargeOptionId = $opt->id;
        $reqCreate->expectedResultOptionId = $opt->id;
        $reqCreate->remarks = 'Awal.';

        $created = $this->reportItemService->create($reqCreate);

        // Update tanpa ubah itemNo
        $reqUpdate = new UserUpdateReportItemRequest();
        $reqUpdate->id = $created->reportItem->id;
        $reqUpdate->itemNo = 1;   // ← sama dengan sebelumnya
        $reqUpdate->targetOptionId = $opt->id;
        $reqUpdate->activityOptionId = $opt->id;
        $reqUpdate->personnelStrengthOptionId = $opt->id;
        $reqUpdate->locationOptionId = $opt->id;
        $reqUpdate->personInChargeOptionId = $opt->id;
        $reqUpdate->expectedResultOptionId = $opt->id;
        $reqUpdate->remarks = 'Sudah diupdate tapi nomor sama.';

        $response = $this->reportItemService->update($reqUpdate);

        self::assertEquals(1, $response->reportItem->itemNo);
        self::assertEquals('Sudah diupdate tapi nomor sama.', $response->reportItem->remarks);
    }

    /* =============================================================
     * DELETE TESTS
     * ============================================================= */

    public function testDeleteReportItemSuccess(): void
    {
        $userId = $this->createUser('delete-success@gmail.com');
        $reportId = $this->createReport('2026-10-09', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        $request = new UserAddReportItemRequest();
        $request->reportId = $reportId;
        $request->itemNo = 1;   // ← manual
        $request->targetOptionId = $opt->id;
        $request->activityOptionId = $opt->id;
        $request->personnelStrengthOptionId = $opt->id;
        $request->locationOptionId = $opt->id;
        $request->personInChargeOptionId = $opt->id;
        $request->expectedResultOptionId = $opt->id;
        $request->remarks = 'Akan segera dihapus.';

        $response = $this->reportItemService->create($request);

        // Hapus
        $this->reportItemService->delete($response->reportItem->id);

        // Pastikan null
        $check = $this->reportItemRepository->findById($response->reportItem->id);
        self::assertNull($check);
    }

    public function testCreateFailsWhenItemNoLessThanOne(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Nomor giat minimal 1');

        $userId = $this->createUser('create-zero-itemno@gmail.com');
        $reportId = $this->createReport('2026-10-10', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        $request = new UserAddReportItemRequest();
        $request->reportId = $reportId;
        $request->itemNo = 0;   // ← nol
        $request->targetOptionId = $opt->id;
        $request->activityOptionId = $opt->id;
        $request->personnelStrengthOptionId = $opt->id;
        $request->locationOptionId = $opt->id;
        $request->personInChargeOptionId = $opt->id;
        $request->expectedResultOptionId = $opt->id;

        $this->reportItemService->create($request);
    }

    public function testUpdateFailsWhenItemNoEmpty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Nomor giat wajib diisi');

        $userId = $this->createUser('update-null-itemno@gmail.com');
        $reportId = $this->createReport('2026-10-11', $userId);

        $opt = $this->createDummyOption('Target', 'Umum');

        // Buat item dulu
        $createReq = new UserAddReportItemRequest();
        $createReq->reportId = $reportId;
        $createReq->itemNo = 1;
        $createReq->targetOptionId = $opt->id;
        $createReq->activityOptionId = $opt->id;
        $createReq->personnelStrengthOptionId = $opt->id;
        $createReq->locationOptionId = $opt->id;
        $createReq->personInChargeOptionId = $opt->id;
        $createReq->expectedResultOptionId = $opt->id;

        $created = $this->reportItemService->create($createReq);

        // Update dengan itemNo null
        $updateReq = new UserUpdateReportItemRequest();
        $updateReq->id = $created->reportItem->id;
        $updateReq->itemNo = null;   // ← null
        $updateReq->targetOptionId = $opt->id;
        $updateReq->activityOptionId = $opt->id;
        $updateReq->personnelStrengthOptionId = $opt->id;
        $updateReq->locationOptionId = $opt->id;
        $updateReq->personInChargeOptionId = $opt->id;
        $updateReq->expectedResultOptionId = $opt->id;

        $this->reportItemService->update($updateReq);
    }
}