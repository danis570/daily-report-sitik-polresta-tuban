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
        $this->reportOptionRepository = new ReportOptionRepository($connection); // 3. INSTANSIASI DI SINI
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);
        $this->reportService = new ReportService($this->reportRepository);
        
        $this->reportItemService = new ReportItemService(
            $this->reportItemRepository,
            $this->reportRepository
        );

        // 4. Bersihkan data (Urutan penting agar aman dari constraint foreign key)
        $this->reportItemRepository->deleteAll();
        $this->reportOptionRepository->deleteAll(); // Bersihkan juga tabel options
        $this->reportRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

        public function testCreateSuccessAndAutoIncrementItemNo()
    {
        // 1. Buat data User & Laporan Induk (Report) terlebih dahulu
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'sitik@polrestatuban.com';
        $userRequest->password = 'tuban123';
        $userResult = $this->userService->register($userRequest);
        $userId = $userResult->user->id;

        $reportRequest = new UserAddReportRequest();
        $reportRequest->reportDate = '2026-10-01';
        $reportRequest->createdBy = $userId;
        $reportResponse = $this->reportService->create($reportRequest);
        $reportId = $reportResponse->report->id;

        // 2. BUAT DATA DUMMY 'REPORT OPTIONS' TERLEBIH DAHULU AGAR ID VALID
        $opt1 = $this->createDummyOption('Target', 'Sasaran 1');
        $opt2 = $this->createDummyOption('Activity', 'Patroli');
        $opt3 = $this->createDummyOption('Personnel', '10 Personel');
        $opt4 = $this->createDummyOption('Location', 'Tuban Kota');
        $opt5 = $this->createDummyOption('PIC', 'Kanit Patroli');
        $opt6 = $this->createDummyOption('Result', 'Aman Terkendali');

        // 3. Simpan Item Kegiatan PERTAMA menggunakan ID yang baru saja dibuat
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

        // Asersi Item Pertama
        self::assertInstanceOf(UserAddReportItemResponse::class, $response1);
        self::assertEquals(1, $response1->reportItem->itemNo);
    }

    // Helper kecil untuk membuat data dummy opsi di test
    private function createDummyOption(string $category, string $name)
    {
        $option = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption();
        $option->category = $category;
        $option->name = $name;
        return $this->reportOptionRepository->save($option);
    }


    public function testCreateReportNotFound()
    {
        // Mencoba menginput kegiatan ke ID laporan fiktif (9999)
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
        // 1. Skenario setup data awal tiruan (dummy)
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'test-update@gmail.com';
        $userRequest->password = 'password';
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
        $requestCreate->activityOptionId = $optActivity1->id; // Giat Awal: Patroli
        $requestCreate->personnelStrengthOptionId = $optPers->id;
        $requestCreate->locationOptionId = $optLoc->id;
        $requestCreate->personInChargeOptionId = $optPic->id;
        $requestCreate->expectedResultOptionId = $optRes->id;
        $requestCreate->remarks = 'Keterangan awal aktivitas dinas.';
        $responseCreate = $this->reportItemService->create($requestCreate);

        // 2. Eksekusi Perubahan Data (Update)
        $requestUpdate = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportItemRequest();
        $requestUpdate->id = $responseCreate->reportItem->id;
        $requestUpdate->targetOptionId = $optTarget->id;
        $requestUpdate->activityOptionId = $optActivity2->id; // Diubah jadi: Sambang Tokoh
        $requestUpdate->personnelStrengthOptionId = $optPers->id;
        $requestUpdate->locationOptionId = $optLoc->id;
        $requestUpdate->personInChargeOptionId = $optPic->id;
        $requestUpdate->expectedResultOptionId = $optRes->id;
        $requestUpdate->remarks = 'Keterangan setelah diperbarui.';

        $responseUpdate = $this->reportItemService->update($requestUpdate);

        // 3. Asersi Perubahan data ViewModel/Domain
        self::assertEquals($optActivity2->id, $responseUpdate->reportItem->activityOptionId);
        self::assertEquals('Keterangan setelah diperbarui.', $responseUpdate->reportItem->remarks);
        // Memastikan nomor urut (item_no) tidak berubah/tetap aman
        self::assertEquals(1, $responseUpdate->reportItem->itemNo);
    }

    public function testDeleteReportItemSuccess()
    {
        // 1. Jalankan skenario pendaftaran data dasar seperti biasa
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'test-delete@gmail.com';
        $userRequest->password = 'password';
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

        // 2. Jalankan eksekusi fungsi hapus di Service
        $this->reportItemService->delete($response->reportItem->id);

        // 3. Pastikan data tidak ditemukan kembali (null) saat dicari
        $check = $this->reportItemRepository->findById($response->reportItem->id);
        self::assertNull($check);
    }

}