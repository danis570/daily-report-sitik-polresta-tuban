<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Config;

use DateTimeImmutable;

/**
 * Provider tanggal merah nasional yang sifatnya FIXED (sama setiap tahun).
 *
 * Untuk MVP, hanya 3 tanggal:
 *  - 1 Januari  (Tahun Baru)
 *  - 17 Agustus (Kemerdekaan RI)
 *  - 25 Desember (Natal)
 *
 * Tanggal merah lain (hijriah, cuti bersama, dll) diinput manual dari form.
 */
class HolidayProvider
{
    /**
     * Format: 'm-d' (bulan-tanggal), karena tanggalnya sama tiap tahun.
     *
     * @var array<string>
     */
    private const FIXED_HOLIDAYS = [
        '01-01', // Tahun Baru
        '08-17', // Kemerdekaan RI
        '12-25', // Natal
    ];

    /**
     * Cek apakah tanggal tertentu adalah tanggal merah fixed nasional.
     */
    public function isHoliday(DateTimeImmutable $date): bool
    {
        return in_array($date->format('m-d'), self::FIXED_HOLIDAYS, true);
    }

    /**
     * Return semua tanggal merah fixed dalam 1 tahun.
     *
     * @return array<DateTimeImmutable>
     */
    public function getHolidays(int $year): array
    {
        $result = [];

        foreach (self::FIXED_HOLIDAYS as $md) {
            [$month, $day] = explode('-', $md);
            $result[] = new DateTimeImmutable(
                sprintf('%04d-%02d-%02d', $year, (int) $month, (int) $day)
            );
        }

        return $result;
    }

    /**
     * Return daftar tanggal merah fixed dalam format 'Y-m-d' untuk tahun tertentu.
     *
     * @return array<string>
     */
    public function getHolidayStrings(int $year): array
    {
        return array_map(
            fn(DateTimeImmutable $d) => $d->format('Y-m-d'),
            $this->getHolidays($year)
        );
    }
}