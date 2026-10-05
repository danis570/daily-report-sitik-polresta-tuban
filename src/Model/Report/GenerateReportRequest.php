<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Report;

class GenerateReportRequest
{
    /**
     * Tanggal mulai (format 'Y-m-d').
     */
    public ?string $startDate = null;

    /**
     * Tanggal akhir (format 'Y-m-d').
     */
    public ?string $endDate = null;

    /**
     * Daftar tanggal merah manual (format 'Y-m-d').
     * Contoh: ['2026-10-05', '2026-10-12'].
     *
     * @var array<string>
     */
    public array $manualHolidays = [];

    /**
     * User yang menjalankan generate (dari session).
     */
    public ?int $createdBy = null;
}