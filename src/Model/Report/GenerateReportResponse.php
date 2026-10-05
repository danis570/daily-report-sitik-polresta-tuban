<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Report;

class GenerateReportResponse
{
    /**
     * Jumlah report yang berhasil di-generate.
     */
    public int $generated = 0;

    /**
     * Jumlah tanggal yang di-skip.
     */
    public int $skipped = 0;

    /**
     * Detail tanggal yang di-skip.
     * Format: [['date' => 'Y-m-d', 'reason' => '...'], ...].
     *
     * @var array<array{date: string, reason: string}>
     */
    public array $skippedDetails = [];

    /**
     * Daftar tanggal yang berhasil di-generate (format 'Y-m-d').
     *
     * @var array<string>
     */
    public array $generatedDates = [];
}