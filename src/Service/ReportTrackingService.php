<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use InvalidArgumentException;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\ReportTrackingRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\ReportTrackingResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;

class ReportTrackingService
{
    public function __construct(
        private ReportItemRepository $reportItemRepository,
        private ReportOptionRepository $reportOptionRepository
    ) {
    }

    public function track(
        ReportTrackingRequest $request
    ): ReportTrackingResponse {
        if ($request->startDate > $request->endDate) {
            throw new InvalidArgumentException(
                'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'
            );
        }

        $option = $this->reportOptionRepository
            ->findById($request->optionId);

        if ($option === null) {
            throw new InvalidArgumentException(
                'Report option tidak ditemukan.'
            );
        }

        if ($option->category !== $request->category) {
            throw new InvalidArgumentException(
                'Kategori option tidak sesuai.'
            );
        }

        $total = $this->reportItemRepository
            ->countOptionByDateRange(
                $request->category,
                $request->optionId,
                $request->startDate,
                $request->endDate
            );

        $response = new ReportTrackingResponse();

        $response->category = $request->category;
        $response->optionId = $option->id;
        $response->optionName = $option->name;
        $response->startDate = $request->startDate->format('Y-m-d');
        $response->endDate = $request->endDate->format('Y-m-d');
        $response->total = $total;

        return $response;
    }
}