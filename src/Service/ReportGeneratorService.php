<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use DateTimeImmutable;
use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Config\HolidayProvider;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Report;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\GenerateReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\GenerateReportResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;

class ReportGeneratorService
{
    /**
     * Mapping hari (DAYOFWEEK) ke report template ID.
     * Senin=66, Selasa=67, Rabu=68, Kamis=69, Jumat=70.
     */
    private const TEMPLATE_MAP = [
        1 => 66, // Senin
        2 => 67, // Selasa
        3 => 68, // Rabu
        4 => 69, // Kamis
        5 => 70, // Jumat
    ];

    /**
     * Maksimal rentang generate (inclusive) dalam hari.
     */
    private const MAX_RANGE_DAYS = 31;

    private ReportRepository $reportRepository;
    private ReportItemRepository $reportItemRepository;
    private HolidayProvider $holidayProvider;

    public function __construct(
        ReportRepository $reportRepository,
        ReportItemRepository $reportItemRepository,
        HolidayProvider $holidayProvider
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportItemRepository = $reportItemRepository;
        $this->holidayProvider = $holidayProvider;
    }

    public function generate(GenerateReportRequest $request): GenerateReportResponse
    {
        // 1. Validasi input → return DateTimeImmutable start & end
        [$start, $end] = $this->validateRequest($request);

        // 2. Ambil tanggal existing di DB untuk anti-duplikat
        $existing = $this->reportRepository->findExistingDatesInRange($start, $end);
        $existingMap = array_flip($existing); // untuk lookup cepat

        // 3. Normalisasi manual holidays ke format 'Y-m-d' + set untuk lookup
        $manualHolidaySet = [];
        foreach ($request->manualHolidays as $h) {
            $manualHolidaySet[$h] = true;
        }

        // 4. Bangun daftar tanggal yang akan di-generate & yang di-skip
        $toGenerate = [];        // array<DateTimeImmutable>
        $skippedDetails = [];    // array<array{date, reason}>

        $cursor = $start;
        while ($cursor <= $end) {
            $dateStr = $cursor->format('Y-m-d');
            $dow = (int) $cursor->format('N'); // 1=Senin ... 7=Minggu

            // 4a. Skip Sabtu/Minggu
            // 4a. Skip Sabtu/Minggu
            if ($dow > 5) {
                $skippedDetails[] = [
                    'date' => $dateStr,
                    'reason' => 'weekend',  // ← ubah ke kode
                ];
                $cursor = $cursor->modify('+1 day');
                continue;
            }

            // 4b. Skip tanggal merah fixed nasional
            if ($this->holidayProvider->isHoliday($cursor)) {
                $skippedDetails[] = [
                    'date' => $dateStr,
                    'reason' => 'national_holiday',
                ];
                $cursor = $cursor->modify('+1 day');
                continue;
            }

            // 4c. Skip tanggal merah manual dari form
            if (isset($manualHolidaySet[$dateStr])) {
                $skippedDetails[] = [
                    'date' => $dateStr,
                    'reason' => 'manual_holiday',
                ];
                $cursor = $cursor->modify('+1 day');
                continue;
            }

            // 4d. Skip kalau sudah ada di DB
            if (isset($existingMap[$dateStr])) {
                $skippedDetails[] = [
                    'date' => $dateStr,
                    'reason' => 'exists',
                ];
                $cursor = $cursor->modify('+1 day');
                continue;
            }

            // 4e. Lolos → masuk daftar generate
            $toGenerate[] = $cursor;
            $cursor = $cursor->modify('+1 day');
        }

        // 5. Eksekusi dalam transaksi (atomic)
        $generated = 0;
        $generatedDates = [];

        if (!empty($toGenerate)) {
            Database::transaction(function () use ($toGenerate, $request, &$generated, &$generatedDates) {
                foreach ($toGenerate as $date) {
                    $templateId = $this->resolveTemplateId($date);

                    $report = new Report();
                    $report->reportDate = $date;
                    $report->createdBy = $request->createdBy;
                    // created_at = report_date - 2 hari, jam 08:00:00
                    $report->createdAt = $date
                        ->modify('-2 days')
                        ->setTime(8, 0, 0);

                    $saved = $this->reportRepository->save($report);

                    // Copy item dari template
                    $this->reportItemRepository->copyItemsToReport(
                        $templateId,
                        $saved->id
                    );

                    $generated++;
                    $generatedDates[] = $date->format('Y-m-d');
                }
            });
        }

        // 6. Bangun response
        $response = new GenerateReportResponse();
        $response->generated = $generated;
        $response->skipped = count($skippedDetails);
        $response->skippedDetails = $skippedDetails;
        $response->generatedDates = $generatedDates;

        return $response;
    }

    /**
     * Validasi request. Return [start, end] sebagai DateTimeImmutable.
     *
     * @return array{0: DateTimeImmutable, 1: DateTimeImmutable}
     */
    private function validateRequest(GenerateReportRequest $request): array
    {
        if ($request->createdBy === null) {
            throw new Exception('Identitas pembuat laporan tidak valid.');
        }

        if (empty($request->startDate)) {
            throw new Exception('Tanggal mulai tidak boleh kosong.');
        }

        if (empty($request->endDate)) {
            throw new Exception('Tanggal akhir tidak boleh kosong.');
        }

        try {
            $start = new DateTimeImmutable($request->startDate);
            $end = new DateTimeImmutable($request->endDate);
        } catch (Exception $e) {
            throw new Exception('Format tanggal tidak valid.');
        }

        // Normalisasi ke awal hari supaya perbandingan konsisten
        $start = $start->setTime(0, 0, 0);
        $end = $end->setTime(0, 0, 0);

        if ($end < $start) {
            throw new Exception('Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.');
        }

        $diffDays = $start->diff($end)->days + 1; // inclusive

        if ($diffDays > self::MAX_RANGE_DAYS) {
            throw new Exception(
                'Rentang tanggal maksimal ' . self::MAX_RANGE_DAYS . ' hari. '
                . 'Rentang Anda: ' . $diffDays . ' hari.'
            );
        }

        return [$start, $end];
    }

    /**
     * Tentukan template ID berdasarkan hari.
     * Senin=66, Selasa=67, Rabu=68, Kamis=69, Jumat=70.
     */
    private function resolveTemplateId(DateTimeImmutable $date): int
    {
        $dow = (int) $date->format('N'); // 1..5 (sudah difilter)

        if (!isset(self::TEMPLATE_MAP[$dow])) {
            throw new Exception(
                'Tidak ada template untuk hari ke-' . $dow
                . ' pada tanggal ' . $date->format('Y-m-d')
            );
        }

        return self::TEMPLATE_MAP[$dow];
    }

    /**
 * Return daftar template ID + nama hari + report_date (format Indonesia),
 * untuk ditampilkan di UI.
 *
 * @return array<int, array{day: int, day_name: string, template_id: int, report_date: string}>
 */
public function getTemplateList(): array
{
    $dayNames = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
    ];

    $bulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $result = [];

    foreach (self::TEMPLATE_MAP as $day => $templateId) {

        $reportDate = '-';
        $report = $this->reportRepository->findById($templateId);

        if ($report !== null && $report->reportDate !== null) {
            $d = $report->reportDate;

            $reportDate = $d->format('j') . ' '
                . ($bulan[(int) $d->format('n')] ?? '')
                . ' ' . $d->format('Y');
        }

        $result[] = [
            'day'         => $day,
            'day_name'    => $dayNames[$day] ?? '-',
            'template_id' => $templateId,
            'report_date' => $reportDate,
        ];
    }

    return $result;
}
}