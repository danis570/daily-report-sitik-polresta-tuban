<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use DateTimeImmutable;
use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceCheckInRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceCheckOutRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceManualRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceQrRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceStatusRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\AttendanceService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

class AttendanceController extends BaseController
{
    private AttendanceService $attendanceService;
    private AttendanceRepository $attendanceRepository;
    private AttendanceStatusRepository $statusRepository;
    private AttendanceQrRepository $qrRepository;

    private const QR_CODE = 'ABSEN-SITIK-TUBAN';

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

        $this->attendanceService = new AttendanceService(
            $this->attendanceRepository,
            $this->statusRepository,
            $this->qrRepository,
            $userRepository,
            $profileRepository
        );
    }

    // ============================================================
    // SCAN PAGE
    // ============================================================

    public function scan(): void
    {
        $userId = $this->currentUserId();

        // State hari ini: none / checked_in / complete
        $todayStatus = $this->attendanceService->getTodayStatus($userId);

        View::render('User', 'User/Attendance/scan', [
            'title' => 'Absensi',
            'current' => 'absen',
            'todayStatus' => $todayStatus,
            'qrCode' => self::QR_CODE,
        ]);
    }

    // ============================================================
    // POST SCAN (AJAX)
    // ============================================================

    public function postScan(): void
    {
        header('Content-Type: application/json');

        try {
            $userId = $this->currentUserId();

            $input = json_decode(file_get_contents('php://input'), true);
            if (!is_array($input)) {
                throw new Exception('Request tidak valid.');
            }

            $qrCode = trim((string) ($input['qr_code'] ?? ''));
            $statusCode = isset($input['status_code']) ? trim((string) $input['status_code']) : null;

            $todayStatus = $this->attendanceService->getTodayStatus($userId);

            // ============================
            // STATE: none → Check-in
            // ============================
            if ($todayStatus['state'] === 'none') {
                if ($statusCode === null || $statusCode === '') {
                    echo json_encode([
                        'success' => false,
                        'action' => 'need_status',
                        'message' => 'Pilih status: Hadir atau Dinas.',
                    ]);
                    return;
                }

                $req = new AttendanceCheckInRequest();
                $req->userId = $userId;
                $req->qrCode = $qrCode;
                $req->statusCode = $statusCode;

                $response = $this->attendanceService->checkIn($req);

                echo json_encode([
                    'success' => true,
                    'action' => 'check_in',
                    'message' => 'Absen masuk berhasil.',
                    'time' => $response->attendance->checkInTime->format('H:i'),
                    'status' => $response->attendance->statusCode,
                ]);
                return;
            }

            // ============================
            // STATE: checked_in → Check-out
            // ============================
            if ($todayStatus['state'] === 'checked_in') {
                $req = new AttendanceCheckOutRequest();
                $req->userId = $userId;
                $req->qrCode = $qrCode;

                $response = $this->attendanceService->checkOut($req);

                echo json_encode([
                    'success' => true,
                    'action' => 'check_out',
                    'message' => 'Absen keluar berhasil.',
                    'time' => $response->attendance->checkOutTime->format('H:i'),
                    'status' => $response->attendance->statusCode,
                ]);
                return;
            }

            // ============================
            // STATE: complete → sudah selesai
            // ============================
            if ($todayStatus['state'] === 'complete') {
                echo json_encode([
                    'success' => false,
                    'action' => 'complete',
                    'message' => 'Anda sudah absen masuk dan keluar hari ini.',
                ]);
                return;
            }

            // ============================
            // STATE: final (IZIN/CUTI/SKT) → tidak bisa apa-apa
            // ============================
            if ($todayStatus['state'] === 'final') {
                echo json_encode([
                    'success' => false,
                    'action' => 'final',
                    'message' => 'Status hari ini ' . $todayStatus['attendance']->statusCode . ', tidak bisa check-in.',
                ]);
                return;
            }

            // Fallback — state tidak dikenal
            echo json_encode([
                'success' => false,
                'message' => 'Status tidak dikenali.',
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // ============================================================
    // MANUAL FORM (dari rumah)
    // ============================================================

    public function manual(): void
    {
        $userId = $this->currentUserId();

        $today = new DateTimeImmutable('today');
        $existing = $this->attendanceRepository->findByUserAndDate($userId, $today);

        // Kalau sudah ada absen hari ini → langsung blokir
        if ($existing !== null) {
            View::flashMessage(
                'Anda sudah memiliki absensi hari ini (' . $existing->statusCode . ').',
                'error'
            );
            header('Location: /absen');
            exit();
        }

        $allStatuses = $this->statusRepository->findAllActive();
        $manualStatuses = array_filter(
            $allStatuses,
            fn($s) => in_array(
                $s->code,
                ['LD', 'P', 'DIKSIP', 'DIK', 'PATSUS', 'IZIN', 'CUTI', 'SKT'],
                true
            )
        );

        $error = $_SESSION['attendance_error'] ?? null;
        $oldInput = $_SESSION['old_attendance_input'] ?? [];

        unset($_SESSION['attendance_error'], $_SESSION['old_attendance_input']);

        View::render('User', 'User/Attendance/manual', [
            'title' => 'Absen Manual',
            'current' => 'absen',
            'manualStatuses' => array_values($manualStatuses),
            'oldInput' => $oldInput,
            'error' => $error,
        ]);
    }

    public function postManual(): void
    {
        try {
            $userId = $this->currentUserId();

            $req = new AttendanceManualRequest();
            $req->userId = $userId;
            $req->statusCode = $_POST['status_code'] ?? '';
            $req->remarks = $_POST['remarks'] ?? null;
            $req->date = $_POST['date'] ?? null;

            $response = $this->attendanceService->manual($req);

            View::flashMessage($response->message);
            header('Location: /absen');
            exit();

        } catch (Exception $e) {
            $_SESSION['old_attendance_input'] = $_POST;
            $_SESSION['attendance_error'] = $e->getMessage();
            header('Location: /absen/manual');
            exit();
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

        View::render('User', 'User/Attendance/rekap-harian', [
            'title' => 'Rekap Absensi Harian',
            'current' => 'absen-rekap',
            'date' => $date,
            'rows' => $rekap['rows'],
            'summary' => $rekap['summary'],
            'attendanceStartDate' => $this->attendanceService->getStartDate(),  // ← BARU
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

        View::render('User', 'User/Attendance/rekap-bulanan', [
            'title' => 'Rekap Absensi Bulanan',
            'current' => 'absen-bulanan',
            'start' => $start,
            'end' => $end,
            'rows' => $rekap['rows'],
            'attendanceStartDate' => $this->attendanceService->getStartDate(),  // ← BARU
        ]);
    }

    // ============================================================
    // HELPERS
    // ============================================================

    private function currentUserId(): int
    {
        $sessionId = $_COOKIE[SessionService::$cookieName] ?? null;

        if (!$sessionId) {
            header('Location: /login');
            exit();
        }

        $currentSession = $this->sessionRepository->findById($sessionId);
        if (!$currentSession) {
            header('Location: /login');
            exit();
        }

        return (int) $currentSession->userId;
    }
}