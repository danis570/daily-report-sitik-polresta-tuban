<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Config\HolidayProvider;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\GenerateReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ReportGeneratorService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

class ReportGenerateController extends BaseController
{
    private ReportGeneratorService $reportGeneratorService;

    public function __construct()
    {
        $connection = Database::getConnection();

        // Repository dasar untuk BaseController
        $userRepository = new UserRepository($connection);
        $profileRepository = new ProfileRepository($connection);
        $sessionRepository = new SessionRepository($connection);

        parent::__construct($userRepository, $profileRepository, $sessionRepository);

        // Service untuk generate
        $reportRepository = new ReportRepository($connection);
        $reportItemRepository = new ReportItemRepository($connection);
        $holidayProvider = new HolidayProvider();

        $this->reportGeneratorService = new ReportGeneratorService(
            $reportRepository,
            $reportItemRepository,
            $holidayProvider
        );
    }

    /**
     * GET /report/generate — tampilkan form generate.
     */
    public function form(): void
    {
        View::render('User', 'User/Report/generate', [
            'title' => 'Generate Laporan Otomatis',
            'current' => 'generate',
            'templateList' => $this->reportGeneratorService->getTemplateList(),
        ]);
    }

    /**
     * POST /report/generate — eksekusi generate.
     */
    public function postGenerate(): void
    {
        // 1. Ambil user ID dari session (pola sama seperti postAdd/postDuplicate)
        $sessionId = $_COOKIE[SessionService::$cookieName] ?? null;
        $userId = null;

        if ($sessionId) {
            $currentSession = $this->sessionRepository->findById($sessionId);
            if ($currentSession) {
                $userId = (int) $currentSession->userId;
            }
        }

        // 2. Bangun DTO
        $request = new GenerateReportRequest();
        $request->startDate = $_POST['start_date'] ?? null;
        $request->endDate = $_POST['end_date'] ?? null;
        $request->createdBy = $userId;
        $request->manualHolidays = $this->parseManualHolidays($_POST['manual_holidays'] ?? '');

        try {
            $response = $this->reportGeneratorService->generate($request);

            // 3. Susun pesan flash
            $message = $this->buildFlashMessage($response);

            View::flashMessage($message, 'success');
            View::redirect('/reports');

        } catch (Exception $e) {
            // 4. Gagal → kembali ke form dengan pesan error
            View::render('User', 'User/Report/generate', [
                'title' => 'Generate Laporan Otomatis',
                'current' => 'generate',
                'error' => $e->getMessage(),
                'oldInput' => $_POST,
            ]);
        }
    }

    /**
     * Parse input tanggal merah manual.
     * Bisa dipisah koma, spasi, atau baris baru.
     *
     * @return array<string>
     */
    private function parseManualHolidays(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }

        // Pisah pakai koma / newline / spasi
        $parts = preg_split('/[\s,]+/', $raw, -1, PREG_SPLIT_NO_EMPTY);

        $result = [];
        foreach ($parts as $part) {
            $date = trim($part);
            if ($date === '') {
                continue;
            }
            // Normalisasi — biar konsisten Y-m-d
            try {
                $d = new \DateTimeImmutable($date);
                $result[] = $d->format('Y-m-d');
            } catch (Exception $e) {
                // Skip tanggal tidak valid
                continue;
            }
        }

        return array_values(array_unique($result));
    }

    /**
     * Susun pesan flash dari response generate.
     */
    private function buildFlashMessage($response): string
    {
        $parts = [];

        // === SUKSES ===
        if ($response->generated > 0) {
            $parts[] = " Berhasil generate {$response->generated} laporan.";
        } else {
            $parts[] = "Tidak ada laporan baru yang di-generate.";
        }

        // === SKIP: kelompokin per alasan ===
        if ($response->skipped > 0) {

            $groups = [
                'weekend' => [],
                'national_holiday' => [],
                'manual_holiday' => [],
                'exists' => [],
            ];

            foreach ($response->skippedDetails as $item) {
                $reason = $item['reason'];
                if (isset($groups[$reason])) {
                    $groups[$reason][] = $item['date'];
                }
            }

            $labels = [
                'weekend' => 'Sabtu/Minggu',
                'national_holiday' => 'Tanggal merah nasional',
                'manual_holiday' => 'Tanggal merah manual',
                'exists' => 'Sudah ada laporan',
            ];

            $skipParts = [];
            foreach ($groups as $key => $dates) {
                $count = count($dates);
                if ($count === 0) {
                    continue;
                }

                // Format tanggal jadi "5 Okt", dll.
                $formatted = array_map(
                    fn($d) => $this->formatShortDate($d),
                    $dates
                );

                // Kalau cuma 1, tampilkan tanggalnya; kalau banyak, tampilkan count + list
                if ($count <= 3) {
                    $skipParts[] = "{$count} × {$labels[$key]} (" . implode(', ', $formatted) . ")";
                } else {
                    $skipParts[] = "{$count} × {$labels[$key]}";
                }
            }

            $parts[] = " Skip " . $response->skipped . " tanggal: " . implode('; ', $skipParts) . '.';
        }

        // === WARNING ===
        $parts[] = ' Mohon periksa kembali barangkali ada tanggal merah yang ter generate.';

        return implode(' ', $parts);
    }

    /**
     * Format 'Y-m-d' → '5 Okt' (short).
     */
    private function formatShortDate(string $dateStr): string
    {
        $bulan = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        try {
            $d = new \DateTimeImmutable($dateStr);
        } catch (\Exception $e) {
            return $dateStr;
        }

        return $d->format('j') . ' ' . ($bulan[(int) $d->format('n')] ?? '');
    }
}