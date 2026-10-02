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

        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        $this->reportRepository->deleteAll();
        $this->userRepository->deleteAll();
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
}