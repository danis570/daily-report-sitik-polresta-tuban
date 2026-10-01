<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class ReportServiceTest extends TestCase
{
    private ReportService $reportService;
    private ReportRepository $reportRepository;
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->reportRepository = new ReportRepository($connection);
        $this->reportService = new ReportService($this->reportRepository);

        // Inisialisasi UserService & UserRepository untuk menyuntikkan data user dummy
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        // Bersihkan data (Urutan penting: hapus child table dulu baru parent table)
        $this->reportRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testCreateSuccess()
    {
        // 1. Buat User dummy terlebih dahulu agar ID-nya ada di database
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'petugas@gmail.com';
        $userRequest->password = 'rahasia';
        $userResult = $this->userService->register($userRequest);

        // Ambil ID user yang berhasil digenerate otomatis oleh database
        $userId = $userResult->user->id;

        // 2. Gunakan ID user tersebut untuk membuat report
        $request = new UserAddReportRequest();
        $request->reportDate = '2026-10-01';
        $request->createdBy = $userId; // ID valid yang terdaftar di tabel users

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
        $request->createdBy = 1; // Tidak apa-apa diisi asal, karena validasi kosong akan memblokir sebelum masuk ke DB

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
        // 1. Buat User dummy terlebih dahulu
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'petugas2@gmail.com';
        $userRequest->password = 'rahasia';
        $userResult = $this->userService->register($userRequest);
        $userId = $userResult->user->id;

        // 2. Simpan laporan pertama
        $request1 = new UserAddReportRequest();
        $request1->reportDate = '2026-10-01';
        $request1->createdBy = $userId;
        $this->reportService->create($request1);

        // 3. Coba simpan laporan kedua dengan tanggal yang sama
        $request2 = new UserAddReportRequest();
        $request2->reportDate = '2026-10-01';
        $request2->createdBy = $userId;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Laporan untuk tanggal 01-10-2026 sudah pernah dibuat.');

        $this->reportService->create($request2);
    }

     public function testUpdateReportSuccess()
    {
        // 1. Setup data user & report awal
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'operator@gmail.com';
        $userRequest->password = 'password';
        $userResult = $this->userService->register($userRequest);

        $createRequest = new UserAddReportRequest();
        $createRequest->reportDate = '2026-10-10';
        $createRequest->createdBy = $userResult->user->id;
        $createResponse = $this->reportService->create($createRequest);

        // 2. Eksekusi pengubahan tanggal laporan ke 2026-10-11
        $updateRequest = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportRequest();
        $updateRequest->id = $createResponse->report->id;
        $updateRequest->reportDate = '2026-10-11';
        $updateRequest->createdBy = $userResult->user->id;

        $updateResponse = $this->reportService->update($updateRequest);

        // 3. Asersi perubahan
        self::assertInstanceOf(\Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportResponse::class, $updateResponse);
        self::assertEquals('2026-10-11', $updateResponse->report->reportDate->format('Y-m-d'));
    }

    public function testDeleteReportSuccess()
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email = 'operator2@gmail.com';
        $userRequest->password = 'password';
        $userResult = $this->userService->register($userRequest);

        $createRequest = new UserAddReportRequest();
        $createRequest->reportDate = '2026-10-12';
        $createRequest->createdBy = $userResult->user->id;
        $createResponse = $this->reportService->create($createRequest);

        // Jalankan fungsi hapus laporan utama
        $this->reportService->delete($createResponse->report->id);

        // Pastikan saat dicari kembali hasilnya null
        $check = $this->reportRepository->findById($createResponse->report->id);
        self::assertNull($check);
    }
}
