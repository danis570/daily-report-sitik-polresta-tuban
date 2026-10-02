<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportItemRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportItemResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportRequest;
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

        $this->reportItemRepository = new ReportItemRepository($connection);
        $this->reportRepository = new ReportRepository($connection);
        $this->reportOptionRepository = new ReportOptionRepository($connection);
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);
        $this->reportService = new ReportService($this->reportRepository);

        $this->reportItemService = new ReportItemService(
            $this->reportItemRepository,
            $this->reportRepository
        );

        // Bersihkan data (urutan penting: anak dulu, lalu parent)
        $this->reportItemRepository->deleteAll();
        $this->reportOptionRepository->deleteAll();
        $this->reportRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testCreateSuccessAndAutoIncrementItemNo()
    {
        // 1. Buat User (EMAIL HARUS @gmail.com, PASSWORD MIN 8 KARAKTER)
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'sitik@gmail.com';        // ✅ diganti
        $userRequest->password = 'tuban123';             // ✅ 8 karakter
        $userResult = $this->userService->register($userRequest);
        $userId = $userResult->user->id;

        $reportRequest = new UserAddReportRequest();
        $reportRequest->reportDate = '2026-10-01';
        $reportRequest->createdBy = $userId;
        $reportResponse = $this->reportService->create($reportRequest);
        $reportId = $reportResponse->report->id;

        // 2. Buat data dummy Report Options
        $opt1 = $this->createDummyOption('Target', 'Sasaran 1');
        $opt2 = $this->createDummyOption('Activity', 'Patroli');
        $opt3 = $this->createDummyOption('Personnel', '10 Personel');
        $opt4 = $this->createDummyOption('Location', 'Tuban Kota');
        $opt5 = $this->createDummyOption('PIC', 'Kanit Patroli');
        $opt6 = $this->createDummyOption('Result', 'Aman Terkendali');

        // 3. Simpan Item Kegiatan
        $request1 = new UserAddReportItemRequest();
        $request1->reportId = $reportId;
        $request1->targetOptionId = $opt1->id;
        $request1->activityOptionId = $opt2->id;
        $request1->personnelStrengthOptionId = $opt3->id;
        $request1->locationOptionId = $opt4->id;
        $request1->personInChargeOptionId = $opt5->id;
        $request1->expectedResultOptionId = $opt6->id;
        $request1->remarks = 'Melaksanakan patroli siber di wilayah Polres Tuban.';

        $response1 = $this->reportItemService->create($request1);

        self::assertInstanceOf(UserAddReportItemResponse::class, $response1);
        self::assertEquals(1, $response1->reportItem->itemNo);
    }

    private function createDummyOption(string $category, string $name)
    {
        $option = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption();
        $option->category = $category;
        $option->name = $name;
        return $this->reportOptionRepository->save($option);
    }

    public function testCreateReportNotFound()
    {
        // Report ID fiktif, options juga fiktif
        $request = new UserAddReportItemRequest();
        $request->reportId = 9999;
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

    public function testUpdateSuccess()
    {
        // 1. Setup User (EMAIL @gmail.com, PASSWORD >= 8)
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'test-update@gmail.com';   // ✅ sudah OK
        $userRequest->password = 'password123';          // ✅ ganti dari 'password' (8 char = OK)
        $userResult = $this->userService->register($userRequest);

        $reportRequest = new UserAddReportRequest();
        $reportRequest->reportDate = '2026-10-05';
        $reportRequest->createdBy = $userResult->user->id;
        $reportResponse = $this->reportService->create($reportRequest);

        $optTarget = $this->createDummyOption('Target', 'Target Awal');
        $optActivity1 = $this->createDummyOption('Activity', 'Patroli');
        $optActivity2 = $this->createDummyOption('Activity', 'Sambang Tokoh');
        $optPers = $this->createDummyOption('Personnel', '2 Personel');
        $optLoc = $this->createDummyOption('Location', 'Tuban');
        $optPic = $this->createDummyOption('PIC', 'Kasi');
        $optRes = $this->createDummyOption('Result', 'Kondusif');

        $requestCreate = new UserAddReportItemRequest();
        $requestCreate->reportId = $reportResponse->report->id;
        $requestCreate->targetOptionId = $optTarget->id;
        $requestCreate->activityOptionId = $optActivity1->id;
        $requestCreate->personnelStrengthOptionId = $optPers->id;
        $requestCreate->locationOptionId = $optLoc->id;
        $requestCreate->personInChargeOptionId = $optPic->id;
        $requestCreate->expectedResultOptionId = $optRes->id;
        $requestCreate->remarks = 'Keterangan awal aktivitas dinas.';
        $responseCreate = $this->reportItemService->create($requestCreate);

        // 2. Update
        $requestUpdate = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportItemRequest();
        $requestUpdate->id = $responseCreate->reportItem->id;
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

    public function testDeleteReportItemSuccess()
    {
        // 1. Setup User (EMAIL @gmail.com, PASSWORD >= 8)
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'test-delete@gmail.com';   // ✅ sudah OK
        $userRequest->password = 'password123';          // ✅ ganti dari 'password'
        $userResult = $this->userService->register($userRequest);

        $reportRequest = new UserAddReportRequest();
        $reportRequest->reportDate = '2026-10-06';
        $reportRequest->createdBy = $userResult->user->id;
        $reportResponse = $this->reportService->create($reportRequest);

        $opt = $this->createDummyOption('Target', 'Umum');

        $request = new UserAddReportItemRequest();
        $request->reportId = $reportResponse->report->id;
        $request->targetOptionId = $opt->id;
        $request->activityOptionId = $opt->id;
        $request->personnelStrengthOptionId = $opt->id;
        $request->locationOptionId = $opt->id;
        $request->personInChargeOptionId = $opt->id;
        $request->expectedResultOptionId = $opt->id;
        $request->remarks = 'Akan segera dihapus.';
        $response = $this->reportItemService->create($request);

        // 2. Hapus
        $this->reportItemService->delete($response->reportItem->id);

        // 3. Pastikan null
        $check = $this->reportItemRepository->findById($response->reportItem->id);
        self::assertNull($check);
    }
}