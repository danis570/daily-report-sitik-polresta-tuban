<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use DateTimeImmutable;
use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceQrRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceStatusRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\AttendancePdfService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\AttendanceService;

class AttendanceAdminController extends BaseController
{
    private AttendanceService $attendanceService;
    private AttendanceRepository $attendanceRepository;
    private AttendanceStatusRepository $statusRepository;
    private AttendanceQrRepository $qrRepository;
    private AttendancePdfService $attendancePdfService;

    public function __construct()
    {
        $connection = Database::getConnection();

        $userRepository = new UserRepository($connection);
        $profileRepository = new ProfileRepository($connection);
        $sessionRepository = new SessionRepository($connection);

        parent::__construct($userRepository, $profileRepository, $sessionRepository);

        $this->attendanceRepository = new AttendanceRepository($connection);
        $this->statusRepository = new AttendanceStatusRepository($connection);
        $this->qrRepository = new AttendanceQrRepository($connection);
        $this->attendancePdfService = new AttendancePdfService();

        $this->attendanceService = new AttendanceService(
            $this->attendanceRepository,
            $this->statusRepository,
            $this->qrRepository,
            $userRepository,
            $profileRepository
        );
    }

    // ============================================================
    // DAFTAR ABSENSI
    // ============================================================

    public function index(): void
    {
        $dateStr = $_GET['date'] ?? (new DateTimeImmutable('today'))->format('Y-m-d');
        $userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;

        try {
            $date = new DateTimeImmutable($dateStr);
        } catch (Exception $e) {
            $date = new DateTimeImmutable('today');
        }

        // Ambil absensi di tanggal itu (atau filter user)
        if ($userId !== null && $userId > 0) {
            $attendance = $this->attendanceRepository->findByUserAndDate($userId, $date);
            $attendances = $attendance ? [$attendance] : [];
        } else {
            $attendances = $this->attendanceRepository->findByDate($date);
        }

        // Ambil profil tiap user
        $rows = [];
        foreach ($attendances as $att) {
            $profile = $this->profileRepository->findByUserId($att->userId);
            $rows[] = [
                'attendance' => $att,
                'profile' => $profile,
            ];
        }

        View::render('Admin', 'Admin/Attendance/index', [
            'title' => 'Kelola Absensi',
            'current' => 'admin-attendance',
            'date' => $date,
            'userId' => $userId,
            'rows' => $rows,
            'allStatuses' => $this->statusRepository->findAll(),
            'attendanceStartDate' => $this->attendanceService->getStartDate(),
        ]);
    }

    // ============================================================
    // EDIT ABSENSI
    // ============================================================

    public function edit(int $id): void
    {
        $attendance = $this->attendanceRepository->findById($id);

        if ($attendance === null) {
            View::flashMessage('Absensi tidak ditemukan.', 'error');
            View::redirect('/admin/attendance');
        }

        $profile = $this->profileRepository->findByUserId($attendance->userId);

        View::render('Admin', 'Admin/Attendance/edit', [
            'title' => 'Edit Absensi',
            'current' => 'admin-attendance',
            'attendance' => $attendance,
            'profile' => $profile,
            'allStatuses' => $this->statusRepository->findAll(),
        ]);
    }

    public function postEdit(int $id): void
    {
        try {
            $attendance = $this->attendanceRepository->findById($id);

            if ($attendance === null) {
                throw new Exception('Absensi tidak ditemukan.');
            }

            $statusCode = $_POST['status_code'] ?? '';
            $remarks = $_POST['remarks'] ?? null;

            // Validasi status
            if (!$this->statusRepository->existsByCode($statusCode)) {
                throw new Exception('Status tidak valid.');
            }

            $attendance->statusCode = $statusCode;
            $attendance->remarks = $remarks;

            $this->attendanceRepository->update($attendance);

            View::flashMessage('Absensi berhasil diperbarui.');
            View::redirect('/admin/attendance?date=' . $attendance->attendanceDate->format('Y-m-d'));

        } catch (Exception $e) {
            $_SESSION['attendance_admin_error'] = $e->getMessage();
            View::redirect('/admin/attendance/edit/' . $id);
        }
    }

    // ============================================================
    // HAPUS ABSENSI
    // ============================================================

    public function delete(int $id): void
    {
        try {
            $attendance = $this->attendanceRepository->findById($id);

            if ($attendance === null) {
                throw new Exception('Absensi tidak ditemukan.');
            }

            $this->attendanceRepository->deleteById($id);

            View::flashMessage('Absensi berhasil dihapus.');
            View::redirect('/admin/attendance?date=' . $attendance->attendanceDate->format('Y-m-d'));

        } catch (Exception $e) {
            View::flashMessage('Gagal menghapus: ' . $e->getMessage(), 'error');
            View::redirect('/admin/attendance');
        }
    }

    // ============================================================
    // REKAP HARIAN
    // ============================================================

    public function rekapHarian(): void
    {
        $dateStr = $_GET['date'] ?? (new DateTimeImmutable('today'))->format('Y-m-d');

        try {
            $date = new DateTimeImmutable($dateStr);
        } catch (Exception $e) {
            $date = new DateTimeImmutable('today');
        }

        $rekap = $this->attendanceService->getRekapHarian($date);

        View::render('Admin', 'Admin/Attendance/rekap-harian', [
            'title' => 'Rekap Harian',
            'current' => 'admin-attendance-rekap',
            'date' => $date,
            'rows' => $rekap['rows'],
            'summary' => $rekap['summary'],
            'attendanceStartDate' => $this->attendanceService->getStartDate(),
        ]);
    }

    // ============================================================
    // REKAP BULANAN
    // ============================================================

    public function rekapBulanan(): void
    {
        $monthStr = $_GET['month'] ?? (new DateTimeImmutable('today'))->format('Y-m');

        try {
            $start = new DateTimeImmutable($monthStr . '-01');
            $end = $start->modify('last day of this month');
        } catch (Exception $e) {
            $start = new DateTimeImmutable('first day of this month');
            $end = $start->modify('last day of this month');
        }

        $rekap = $this->attendanceService->getRekapBulanan($start, $end);

        View::render('Admin', 'Admin/Attendance/rekap-bulanan', [
            'title' => 'Rekap Bulanan',
            'current' => 'admin-attendance-bulanan',
            'start' => $start,
            'end' => $end,
            'rows' => $rekap['rows'],
            'attendanceStartDate' => $this->attendanceService->getStartDate(),
        ]);
    }

    // ============================================================
    // KELOLA QR
    // ============================================================

    public function qr(): void
    {
        $qrList = $this->qrRepository->findActive();

        View::render('Admin', 'Admin/Attendance/qr', [
            'title' => 'Kelola QR Absensi',
            'current' => 'admin-attendance-qr',
            'qrList' => $qrList,
        ]);
    }

    // ============================================================
    // KELOLA STATUS
    // ============================================================

    public function status(): void
    {
        $statusList = $this->statusRepository->findAll();

        View::render('Admin', 'Admin/Attendance/status', [
            'title' => 'Status Absensi',
            'current' => 'admin-attendance-status',
            'statusList' => $statusList,
            'statusRepository' => $this->statusRepository,   // ← BARU
        ]);
    }

    // ============================================================
// STATUS — ADD
// ============================================================

    public function addStatus(): void
    {
        View::render('Admin', 'Admin/Attendance/status-form', [
            'title' => 'Tambah Status Absensi',
            'current' => 'admin-attendance-status',
            'mode' => 'add',
            'status' => null,
        ]);
    }

    public function postAddStatus(): void
    {
        try {
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $label = trim($_POST['label'] ?? '');
            $sortOrder = (int) ($_POST['sort_order'] ?? 0);

            if ($code === '') {
                throw new Exception('Kode status wajib diisi.');
            }
            if ($label === '') {
                throw new Exception('Label wajib diisi.');
            }
            if ($this->statusRepository->existsByCode($code)) {
                throw new Exception('Kode sudah ada. Gunakan kode lain.');
            }

            $status = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\AttendanceStatus();
            $status->code = $code;
            $status->label = $label;
            $status->sortOrder = $sortOrder;
            $status->isActive = true;

            $this->statusRepository->save($status);

            View::flashMessage("Status '{$label}' berhasil ditambahkan.");
            View::redirect('/admin/attendance/status');

        } catch (Exception $e) {
            $_SESSION['status_error'] = $e->getMessage();
            $_SESSION['status_old'] = $_POST;
            View::redirect('/admin/attendance/status/add');
        }
    }

    // ============================================================
// STATUS — EDIT
// ============================================================

    public function editStatus(string $code): void
    {
        $status = $this->statusRepository->findByCode($code);

        if ($status === null) {
            View::flashMessage('Status tidak ditemukan.', 'error');
            View::redirect('/admin/attendance/status');
        }

        View::render('Admin', 'Admin/Attendance/status-form', [
            'title' => 'Edit Status Absensi',
            'current' => 'admin-attendance-status',
            'mode' => 'edit',
            'status' => $status,
        ]);
    }

    public function postEditStatus(string $code): void
    {
        try {
            $oldStatus = $this->statusRepository->findByCode($code);
            if ($oldStatus === null) {
                throw new Exception('Status tidak ditemukan.');
            }

            $newCode = strtoupper(trim($_POST['code'] ?? ''));
            $label = trim($_POST['label'] ?? '');
            $sortOrder = (int) ($_POST['sort_order'] ?? 0);
            $isActive = isset($_POST['is_active']) && $_POST['is_active'] == '1';

            if ($newCode === '') {
                throw new Exception('Kode status wajib diisi.');
            }
            if ($label === '') {
                throw new Exception('Label wajib diisi.');
            }

            // Kalau kode diubah → cek duplikat
            if ($newCode !== $code && $this->statusRepository->existsByCode($newCode)) {
                throw new Exception('Kode sudah dipakai status lain.');
            }

            // Kalau kode diubah & sedang dipakai di attendances → update relasi
            if ($newCode !== $code) {
                $usage = $this->statusRepository->countUsage($code);
                if ($usage > 0) {
                    // Update semua attendances dulu
                    $stmt = \Unirow2026\DailyReportSitikPolrestaTuban\App\Database::getConnection()
                        ->prepare("UPDATE attendances SET status_code = ? WHERE status_code = ?");
                    $stmt->execute([$newCode, $code]);
                }
                // Hapus kode lama, insert baru
                $this->statusRepository->delete($code);

                $status = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\AttendanceStatus();
                $status->code = $newCode;
                $status->label = $label;
                $status->sortOrder = $sortOrder;
                $status->isActive = $isActive;
                $this->statusRepository->save($status);

            } else {
                // Update biasa
                $oldStatus->label = $label;
                $oldStatus->sortOrder = $sortOrder;
                $oldStatus->isActive = $isActive;
                $this->statusRepository->update($oldStatus);
            }

            View::flashMessage('Status berhasil diperbarui.');
            View::redirect('/admin/attendance/status');

        } catch (Exception $e) {
            $_SESSION['status_error'] = $e->getMessage();
            View::redirect('/admin/attendance/status/edit/' . urlencode($code));
        }
    }

    // ============================================================
// STATUS — DELETE
// ============================================================

    public function deleteStatus(string $code): void
    {
        try {
            $status = $this->statusRepository->findByCode($code);
            if ($status === null) {
                throw new Exception('Status tidak ditemukan.');
            }

            // Cek penggunaan
            $usage = $this->statusRepository->countUsage($code);
            if ($usage > 0) {
                throw new Exception(
                    "Status '{$code}' sedang dipakai di {$usage} data absensi. Tidak bisa dihapus."
                );
            }

            $this->statusRepository->delete($code);

            View::flashMessage("Status '{$code}' berhasil dihapus.");
            View::redirect('/admin/attendance/status');

        } catch (Exception $e) {
            View::flashMessage($e->getMessage(), 'error');
            View::redirect('/admin/attendance/status');
        }
    }

    public function pdfHarian(): void
    {
        $dateStr = $_GET['date'] ?? (new DateTimeImmutable('today'))->format('Y-m-d');

        try {
            $date = new DateTimeImmutable($dateStr);
        } catch (Exception $e) {
            $date = new DateTimeImmutable('today');
        }

        $rekap = $this->attendanceService->getRekapHarian($date);

        // Format tanggal Indonesia
        $hariArr = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $bulanArr = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $hariIndo = $hariArr[$date->format('l')] ?? $date->format('l');
        $tanggalIndo = $date->format('j') . ' ' . $bulanArr[(int) $date->format('n')] . ' ' . $date->format('Y');

        $statusCodes = ['H', 'D', 'P', 'DIKSIP', 'DIK', 'PATSUS', 'LD', 'IZIN', 'CUTI', 'SKT', 'TK'];

        // Render HTML
        ob_start();
        $mode = 'harian';   // ← PENTING
        $titleDocument = 'ABSENSI KEHADIRAN APEL PAGI ANGGOTA SI TIK POLRES TUBAN';
        $rows = $rekap['rows'];
        $summary = $rekap['summary'];
        require __DIR__ . '/../View/Admin/Attendance/pdf.php';
        $html = ob_get_clean();

        // Generate PDF
        $pdf = $this->attendancePdfService->generate($html);

        // Output
        $filename = 'absensi-' . $date->format('Y-m-d') . '.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdf));

        echo $pdf;
        exit();
    }

    public function pdfBulanan(): void
    {
        $monthStr = $_GET['month'] ?? (new DateTimeImmutable('today'))->format('Y-m');

        try {
            $start = new DateTimeImmutable($monthStr . '-01');
            $end = $start->modify('last day of this month');
        } catch (Exception $e) {
            $start = new DateTimeImmutable('first day of this month');
            $end = $start->modify('last day of this month');
        }

        $rekap = $this->attendanceService->getRekapBulanan($start, $end);

        // Format periode Indonesia
        $bulanArr = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $periodeIndo = $bulanArr[(int) $start->format('n')] . ' ' . $start->format('Y');

        $statusCodes = ['H', 'D', 'P', 'DIKSIP', 'DIK', 'PATSUS', 'LD', 'IZIN', 'CUTI', 'SKT', 'TK'];

        // Render HTML
        ob_start();
        $mode = 'bulanan';   // ← PENTING
        $titleDocument = 'REKAP ABSENSI BULANAN ANGGOTA SI TIK POLRES TUBAN';
        $rows = $rekap['rows'];
        require __DIR__ . '/../View/Admin/Attendance/pdf.php';
        $html = ob_get_clean();

        // Generate PDF
        $pdf = $this->attendancePdfService->generate($html);

        // Output
        $filename = 'rekap-absensi-' . $start->format('Y-m') . '.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdf));

        echo $pdf;
        exit();
    }

    /**
     * Generate PDF bulanan, format harian (per hari 2 halaman).
     * Mirip pdfRange() di ReportController.
     */
    public function pdfBulananHarian(): void
    {
        // =====================================
        // 1. Ambil parameter bulan
        // =====================================
        $monthStr = $_GET['month'] ?? (new DateTimeImmutable('today'))->format('Y-m');

        try {
            $start = new DateTimeImmutable($monthStr . '-01');
            $end = $start->modify('last day of this month');
        } catch (Exception $e) {
            $start = new DateTimeImmutable('first day of this month');
            $end = $start->modify('last day of this month');
        }

        // =====================================
        // 2. Format periode Indonesia
        // =====================================
        $bulanArr = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $hariArr = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $periodeIndo = $bulanArr[(int) $start->format('n')] . ' ' . $start->format('Y');

        $statusCodes = ['H', 'D', 'P', 'DIKSIP', 'DIK', 'PATSUS', 'LD', 'IZIN', 'CUTI', 'SKT', 'TK'];

        // =====================================
        // 3. Ambil cutoff date
        // =====================================
        $attendanceStartDate = $this->attendanceService->getStartDate();

        // =====================================
        // 4. Loop tanggal dalam bulan
        // =====================================
        $dailyData = [];

        $cursor = $start;
        while ($cursor <= $end) {

            $dow = (int) $cursor->format('N');

            // Skip weekend
            if ($dow > 5) {
                $cursor = $cursor->modify('+1 day');
                continue;
            }

            // Skip sebelum cutoff
            if ($cursor < $attendanceStartDate) {
                $cursor = $cursor->modify('+1 day');
                continue;
            }

            // Ambil rekap harian untuk tanggal ini
            $rekap = $this->attendanceService->getRekapHarian($cursor);

            $hariIndo = $hariArr[$cursor->format('l')] ?? $cursor->format('l');
            $tanggalIndo = $cursor->format('j') . ' ' . $bulanArr[(int) $cursor->format('n')] . ' ' . $cursor->format('Y');

            $dailyData[] = [
                'date' => $cursor,
                'hariIndo' => $hariIndo,
                'tanggalIndo' => $tanggalIndo,
                'rows' => $rekap['rows'],
                'summary' => $rekap['summary'],
            ];

            $cursor = $cursor->modify('+1 day');
        }

        // =====================================
        // 5. Kalau kosong → balik ke rekap
        // =====================================
        if (empty($dailyData)) {
            View::flashMessage('Tidak ada hari kerja di bulan ini.', 'error');
            View::redirect('/admin/attendance/rekap-bulanan?month=' . $start->format('Y-m'));
            return;
        }

        // =====================================
        // 6. Render HTML
        // =====================================
        ob_start();
        $mode = 'harian-range';
        $titleDocument = 'ABSENSI KEHADIRAN ANGGOTA SI TIK POLRES TUBAN';
        require __DIR__ . '/../View/Admin/Attendance/pdf.php';
        $html = ob_get_clean();

        // =====================================
        // 7. Generate PDF
        // =====================================
        $pdf = $this->attendancePdfService->generate($html);

        // =====================================
        // 8. Format filename
        // =====================================
        $bulanSlug = strtolower($bulanArr[(int) $start->format('n')]) . '-' . $start->format('Y');
        $filename = 'absensi-harian-' . $bulanSlug . '.pdf';

        // =====================================
        // 9. Header PDF
        // =====================================
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdf));

        // =====================================
        // 10. Output
        // =====================================
        echo $pdf;
        exit();
    }
}