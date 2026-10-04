<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportItem;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class ReportOptionServiceTest extends TestCase
{
    private ReportOptionService $reportOptionService;
    private ReportOptionRepository $reportOptionRepository;
    private ReportItemRepository $reportItemRepository;
    private ReportRepository $reportRepository;
    private UserRepository $userRepository;
    private UserService $userService;
    private ReportService $reportService;

    protected function setUp(): void
    {
        Database::clearConnection();
        $connection = Database::getConnection();

        // Repository
        $this->reportOptionRepository = new ReportOptionRepository($connection);
        $this->reportItemRepository   = new ReportItemRepository($connection);
        $this->reportRepository       = new ReportRepository($connection);
        $this->userRepository         = new UserRepository($connection);

        // Service
        $this->userService   = new UserService($this->userRepository);
        $this->reportService = new ReportService(
            $this->reportRepository,
            $this->reportItemRepository
        );
        $this->reportOptionService = new ReportOptionService(
            $this->reportOptionRepository,
            $this->reportItemRepository
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

    private function createUser(string $email): int
    {
        $userRequest = new UserRegisterRequest();
        $userRequest->email    = $email;
        $userRequest->password = 'password123';

        $userResult = $this->userService->register($userRequest);

        return $userResult->user->id;
    }

    private function createReport(string $date, int $userId): int
    {
        $reportRequest = new UserAddReportRequest();
        $reportRequest->reportDate = $date;
        $reportRequest->createdBy  = $userId;

        $reportResponse = $this->reportService->create($reportRequest);

        return $reportResponse->report->id;
    }

    private function createDummyOption(string $category, string $name): ReportOption
    {
        $option = new ReportOption();
        $option->category    = $category;
        $option->name        = $name;
        $option->description = null;

        return $this->reportOptionRepository->save($option);
    }

    private function createItemUsingOption(int $reportId, int $itemNo, int $optionId): ReportItem
    {
        $item = new ReportItem();
        $item->reportId                   = $reportId;
        $item->itemNo                     = $itemNo;
        $item->targetOptionId             = $optionId;
        $item->activityOptionId           = $optionId;
        $item->personnelStrengthOptionId  = $optionId;
        $item->locationOptionId           = $optionId;
        $item->personInChargeOptionId     = $optionId;
        $item->expectedResultOptionId     = $optionId;
        $item->remarks                    = 'Test item';

        return $this->reportItemRepository->save($item);
    }

    /* =============================================================
     * DELETE TESTS — Validasi hapus opsi
     * ============================================================= */

    public function testDeleteSuccess(): void
    {
        // Opsi yang belum dipakai → boleh dihapus
        $option = $this->createDummyOption('target', 'Opsi Baru');

        $this->reportOptionService->delete($option->id);

        $found = $this->reportOptionRepository->findById($option->id);
        self::assertNull($found);
    }

    public function testDeleteFailsWhenNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('tidak ditemukan');

        $this->reportOptionService->delete(999999);
    }

    public function testDeleteFailsWhenInUse(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('masih digunakan');

        // Setup: opsi dipakai di report item
        $userId   = $this->createUser('delete-in-use@gmail.com');
        $reportId = $this->createReport('2026-10-01', $userId);

        $option = $this->createDummyOption('activity', 'Opsi Dipakai');

        $this->createItemUsingOption($reportId, 1, $option->id);

        // Coba hapus → harus gagal
        $this->reportOptionService->delete($option->id);
    }

    public function testDeleteSuccessAfterUsageRemoved(): void
    {
        // Setup
        $userId   = $this->createUser('delete-after@gmail.com');
        $reportId = $this->createReport('2026-10-02', $userId);

        $option = $this->createDummyOption('activity', 'Opsi akan dihapus');

        $item = $this->createItemUsingOption($reportId, 1, $option->id);

        // Hapus item dulu
        $this->reportItemRepository->deleteById($item->id);

        // Sekarang hapus opsi → harus sukses
        $this->reportOptionService->delete($option->id);

        $found = $this->reportOptionRepository->findById($option->id);
        self::assertNull($found);
    }

    public function testDeleteErrorMessageContainsUsageCount(): void
    {
        // Setup: opsi dipakai 1×
        $userId   = $this->createUser('delete-count@gmail.com');
        $reportId = $this->createReport('2026-10-03', $userId);

        $option = $this->createDummyOption('activity', 'Opsi count');

        $this->createItemUsingOption($reportId, 1, $option->id);

        try {
            $this->reportOptionService->delete($option->id);
            self::fail('Seharusnya throw Exception.');
        } catch (Exception $e) {
            // Pesan error harus sebut "1"
            self::assertStringContainsString('1', $e->getMessage());
        }
    }

    public function testDeleteMultipleItemsStillFails(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('masih digunakan');

        $userId    = $this->createUser('delete-multi@gmail.com');
        $reportId1 = $this->createReport('2026-10-04', $userId);
        $reportId2 = $this->createReport('2026-10-05', $userId);

        $option = $this->createDummyOption('activity', 'Opsi Multi');

        // Pakai opsi di 2 item berbeda
        $this->createItemUsingOption($reportId1, 1, $option->id);
        $this->createItemUsingOption($reportId2, 1, $option->id);

        $this->reportOptionService->delete($option->id);
    }
}