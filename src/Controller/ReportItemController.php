<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportItemRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ReportItemService;

class ReportItemController extends BaseController
{
    private ReportRepository $reportRepository;
    private ReportOptionRepository $reportOptionRepository;
    private ReportItemRepository $reportItemRepository;
    private ReportItemService $reportItemService;

    public function __construct()
    {
        $connection = Database::getConnection();

        $userRepository = new UserRepository($connection);
        $profileRepository = new ProfileRepository($connection);
        $sessionRepository = new SessionRepository($connection);
        parent::__construct($userRepository, $profileRepository, $sessionRepository);

        $this->reportRepository = new ReportRepository($connection);
        $this->reportOptionRepository = new ReportOptionRepository($connection);
        $this->reportItemRepository = new ReportItemRepository($connection);
        $this->reportItemService = new ReportItemService($this->reportItemRepository, $this->reportRepository);
    }

    public function reportItemAdd(string $date)
    {
        try {
            $reportDate = new \DateTimeImmutable($date);
        } catch (Exception $e) {
            View::redirect('/reports');
        }

        $report = $this->reportRepository->findByDate($reportDate);
        if ($report === null) {
            View::redirect('/reports');
        }

        $options = $this->reportOptionRepository->findAll();
        $formattedDate = $this->formatTanggalIndo($report->reportDate);

        // ✅ Hitung nomor berikutnya (default isian form)
        $existingItems = $this->reportItemRepository->findByReportId($report->id);
        $nextItemNo = 1;
        if (!empty($existingItems)) {
            $maxNo = max(array_map(fn($i) => (int) $i->itemNo, $existingItems));
            $nextItemNo = $maxNo + 1;
        }

        View::render('User', 'User/Report/report-item-add', [
            'title' => 'Input Kegiatan Laporan — ' . $date,
            'current' => 'report',
            'report' => $report,
            'date' => $date,
            'formattedDate' => $formattedDate,
            'options' => $options,
            'nextItemNo' => $nextItemNo,   // ← tambahan
        ]);
    }

    public function postReportItemAdd(string $date)
    {
        try {
            $reportDate = new \DateTimeImmutable($date);
        } catch (Exception $e) {
            View::redirect('/reports');
        }

        $report = $this->reportRepository->findByDate($reportDate);
        if ($report === null) {
            View::redirect('/reports');
        }

        $request = new UserAddReportItemRequest();
        $request->reportId = $report->id;
        $request->itemNo = isset($_POST['item_no']) ? (int) $_POST['item_no'] : null;   // ← tambahan
        $request->targetOptionId = isset($_POST['target_option_id']) ? (int) $_POST['target_option_id'] : null;
        $request->activityOptionId = isset($_POST['activity_option_id']) ? (int) $_POST['activity_option_id'] : null;
        $request->personnelStrengthOptionId = isset($_POST['personnel_strength_option_id']) ? (int) $_POST['personnel_strength_option_id'] : null;
        $request->locationOptionId = isset($_POST['location_option_id']) ? (int) $_POST['location_option_id'] : null;
        $request->personInChargeOptionId = isset($_POST['person_in_charge_option_id']) ? (int) $_POST['person_in_charge_option_id'] : null;
        $request->expectedResultOptionId = isset($_POST['expected_result_option_id']) ? (int) $_POST['expected_result_option_id'] : null;
        $request->remarks = $_POST['remarks'] ?? null;

        try {
            $this->reportItemService->create($request);

            View::flashMessage("Item kegiatan baru berhasil ditambahkan!");
            header("Location: /report/" . $date);
            exit();

        } catch (Exception $exception) {
            $options = $this->reportOptionRepository->findAll();
            $formattedDate = $this->formatTanggalIndo($report->reportDate);

            // Hitung ulang nomor berikutnya untuk default form
            $existingItems = $this->reportItemRepository->findByReportId($report->id);
            $nextItemNo = 1;
            if (!empty($existingItems)) {
                $maxNo = max(array_map(fn($i) => (int) $i->itemNo, $existingItems));
                $nextItemNo = $maxNo + 1;
            }

            View::render('User', 'User/Report/report-item-add', [
                'title' => 'Input Kegiatan Laporan — ' . $date,
                'current' => 'report',
                'error' => $exception->getMessage(),
                'report' => $report,
                'date' => $date,
                'formattedDate' => $formattedDate,
                'options' => $options,
                'nextItemNo' => $nextItemNo,
            ]);
        }
    }

    public function editItem(int $id)
    {
        // 1. Ambil data asli ReportItem berdasarkan ID
        $reportItem = $this->reportItemRepository->findById($id);
        if ($reportItem === null) {
            View::redirect('/reports');
        }

        // 2. Ambil data Report Induknya untuk mendapatkan informasi tanggal
        $report = $this->reportRepository->findById($reportItem->reportId);
        if ($report === null) {
            View::redirect('/reports');
        }

        $date = $report->reportDate->format('Y-m-d');
        $formattedDate = $this->formatTanggalIndo($report->reportDate);

        // 3. Ambil seluruh opsi referensi dari database untuk form dropdown
        $options = $this->reportOptionRepository->findAll();

        // 4. Render ke halaman formulir edit item kegiatan
        View::render('User', 'User/Report/report-item-edit', [
            'title' => 'Ubah Kegiatan Laporan — ' . $date,
            'current' => 'report',
            'reportItem' => $reportItem,
            'report' => $report,
            'date' => $date,
            'formattedDate' => $formattedDate,
            'options' => $options
        ]);
    }

    public function postEditItem(int $id)
    {
        $reportItem = $this->reportItemRepository->findById($id);
        if ($reportItem === null) {
            View::redirect('/reports');
        }

        $report = $this->reportRepository->findById($reportItem->reportId);
        if ($report === null) {
            View::redirect('/reports');
        }

        $date = $report->reportDate->format('Y-m-d');

        // 1. Siapkan DTO Request Update
        $request = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportItemRequest();
        $request->id = $id;
        $request->itemNo = isset($_POST['item_no']) ? (int) $_POST['item_no'] : null;   // ← TAMBAHAN
        $request->targetOptionId = isset($_POST['target_option_id']) ? (int) $_POST['target_option_id'] : null;
        $request->activityOptionId = isset($_POST['activity_option_id']) ? (int) $_POST['activity_option_id'] : null;
        $request->personnelStrengthOptionId = isset($_POST['personnel_strength_option_id']) ? (int) $_POST['personnel_strength_option_id'] : null;
        $request->locationOptionId = isset($_POST['location_option_id']) ? (int) $_POST['location_option_id'] : null;
        $request->personInChargeOptionId = isset($_POST['person_in_charge_option_id']) ? (int) $_POST['person_in_charge_option_id'] : null;
        $request->expectedResultOptionId = isset($_POST['expected_result_option_id']) ? (int) $_POST['expected_result_option_id'] : null;
        $request->remarks = $_POST['remarks'] ?? null;

        try {
            // 2. Eksekusi pembaruan di Layer Service
            $this->reportItemService->update($request);

            // 3. Sukses: Set flash message dan alihkan kembali ke detail laporan
            View::flashMessage("Item kegiatan berhasil diperbarui!");
            header("Location: /report/" . $date);
            exit();

        } catch (Exception $exception) {
            // 4. Gagal: Tampilkan kembali formulir edit dengan pesan error
            $options = $this->reportOptionRepository->findAll();
            $formattedDate = $this->formatTanggalIndo($report->reportDate);

            View::render('User', 'User/Report/report-item-edit', [
                'title' => 'Ubah Kegiatan Laporan — ' . $date,
                'current' => 'report',
                'error' => $exception->getMessage(),
                'reportItem' => $reportItem,
                'report' => $report,
                'date' => $date,
                'formattedDate' => $formattedDate,
                'options' => $options,
            ]);
        }
    }

    public function postDeleteItem(int $id)
    {
        $reportItem = $this->reportItemRepository->findById($id);
        if ($reportItem === null) {
            View::redirect('/reports');
        }

        $report = $this->reportRepository->findById($reportItem->reportId);
        if ($report === null) {
            View::redirect('/reports');
        }

        $date = $report->reportDate->format('Y-m-d');

        try {
            // 1. Eksekusi penghapusan di Layer Service
            $this->reportItemService->delete($id);

            // 2. Sukses: Set flash message dan alihkan kembali ke halaman detail
            View::flashMessage("Item kegiatan berhasil dihapus!");
            header("Location: /report/" . $date);
            exit();

        } catch (Exception $exception) {
            // 3. Gagal: Kirim pesan error melalui mekanisme redirect dengan session (flash) atau sesuaikan dengan sistem Anda
            View::flashMessage("Gagal menghapus kegiatan: " . $exception->getMessage());
            header("Location: /report/" . $date);
            exit();
        }
    }


    private function formatTanggalIndo(\DateTimeImmutable $date): string
    {
        $hariArr = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $bulanArr = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $englishDay = $date->format('l');
        $dayNum = $date->format('d');
        $monthNum = $date->format('m');
        $yearNum = $date->format('Y');

        $hariIndo = $hariArr[$englishDay] ?? $englishDay;
        $bulanIndo = $bulanArr[$monthNum] ?? $monthNum;

        return "{$hariIndo}, {$dayNum} {$bulanIndo} {$yearNum}";
    }

}
