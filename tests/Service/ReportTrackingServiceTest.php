<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\ReportTrackingRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;

class ReportTrackingServiceTest extends TestCase
{
    private ReportTrackingService $reportTrackingService;

    /**
     * Tidak menyimpan mock di setUp, karena tiap test butuh
     * konfigurasi berbeda (stub vs mock with expects).
     */
    protected function setUp(): void
    {
        // kosongkan; tiap test akan buat sendiri
    }

    /**
     * Helper: bikin service dengan stub (tanpa expects).
     * Dipakai untuk test yang TIDAK butuh verifikasi pemanggilan method.
     */
    private function makeService(
        ?ReportItemRepository $reportItemRepository = null,
        ?ReportOptionRepository $reportOptionRepository = null
    ): ReportTrackingService {
        $reportItemRepository   = $reportItemRepository
            ?? $this->createStub(ReportItemRepository::class);

        $reportOptionRepository = $reportOptionRepository
            ?? $this->createStub(ReportOptionRepository::class);

        return new ReportTrackingService(
            $reportItemRepository,
            $reportOptionRepository
        );
    }

    public function testTrackSuccess(): void
    {
        $option = new ReportOption();
        $option->id = 51;
        $option->category = 'activity';
        $option->name = 'Pengarahan Kasi Tik';

        $startDate = new DateTimeImmutable('2026-10-01');
        $endDate = new DateTimeImmutable('2026-10-09');

        // ✅ Pakai createMock() karena BUTUH expects()
        $reportOptionRepository = $this->createMock(ReportOptionRepository::class);
        $reportOptionRepository
            ->expects(self::once())
            ->method('findById')
            ->with(51)
            ->willReturn($option);

        $reportItemRepository = $this->createMock(ReportItemRepository::class);
        $reportItemRepository
            ->expects(self::once())
            ->method('countOptionByDateRange')
            ->with('activity', 51, $startDate, $endDate)
            ->willReturn(2);

        $service = new ReportTrackingService(
            $reportItemRepository,
            $reportOptionRepository
        );

        $request = new ReportTrackingRequest();
        $request->category = 'activity';
        $request->optionId = 51;
        $request->startDate = $startDate;
        $request->endDate = $endDate;

        $response = $service->track($request);

        self::assertSame('activity', $response->category);
        self::assertSame(51, $response->optionId);
        self::assertSame('Pengarahan Kasi Tik', $response->optionName);
        self::assertSame('2026-10-01', $response->startDate);
        self::assertSame('2026-10-09', $response->endDate);
        self::assertSame(2, $response->total);
    }

    public function testTrackWhenStartDateIsAfterEndDate(): void
    {
        // ✅ Tidak ada mock/stub — pakai helper
        $service = $this->makeService();

        $request = new ReportTrackingRequest();
        $request->category = 'activity';
        $request->optionId = 51;
        $request->startDate = new DateTimeImmutable('2026-10-09');
        $request->endDate = new DateTimeImmutable('2026-10-01');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'
        );

        $service->track($request);
    }

    public function testTrackWhenOptionNotFound(): void
    {
        // ✅ Pakai createMock() karena BUTUH expects()
        $reportOptionRepository = $this->createMock(ReportOptionRepository::class);
        $reportOptionRepository
            ->expects(self::once())
            ->method('findById')
            ->with(51)
            ->willReturn(null);

        $reportItemRepository = $this->createStub(ReportItemRepository::class);

        $service = new ReportTrackingService(
            $reportItemRepository,
            $reportOptionRepository
        );

        $request = new ReportTrackingRequest();
        $request->category = 'activity';
        $request->optionId = 51;
        $request->startDate = new DateTimeImmutable('2026-10-01');
        $request->endDate = new DateTimeImmutable('2026-10-09');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Report option tidak ditemukan.');

        $service->track($request);
    }

    public function testTrackWhenCategoryDoesNotMatchOption(): void
    {
        $option = new ReportOption();
        $option->id = 51;
        $option->category = 'target';
        $option->name = 'Pengarahan Kasi Tik';

        // ✅ Pakai createMock() karena BUTUH expects()
        $reportOptionRepository = $this->createMock(ReportOptionRepository::class);
        $reportOptionRepository
            ->expects(self::once())
            ->method('findById')
            ->with(51)
            ->willReturn($option);

        $reportItemRepository = $this->createStub(ReportItemRepository::class);

        $service = new ReportTrackingService(
            $reportItemRepository,
            $reportOptionRepository
        );

        $request = new ReportTrackingRequest();
        $request->category = 'activity';
        $request->optionId = 51;
        $request->startDate = new DateTimeImmutable('2026-10-01');
        $request->endDate = new DateTimeImmutable('2026-10-09');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Kategori option tidak sesuai.');

        $service->track($request);
    }
}