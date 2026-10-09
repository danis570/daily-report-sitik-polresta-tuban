<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use DateTimeImmutable;
use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Attendance;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceCheckInRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceCheckOutRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceManualRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceQrRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceStatusRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class AttendanceService
{
    /**
     * Tanggal mulai sistem absensi aktif.
     * Auto-TK hanya berlaku untuk tanggal >= tanggal ini.
     */
    private const ATTENDANCE_START_DATE = '2026-10-01';
    /**
     * Status yang bisa multi-scan (check-in + check-out).
     * HANYA Hadir & Dinas.
     */
    private const MULTI_SCAN_STATUSES = ['H', 'D'];

    /**
     * Status manual — hanya boleh 1× absen, tidak bisa check-in/out.
     */
    private const SINGLE_SCAN_STATUSES = [
        'LD',
        'P',
        'DIKSIP',
        'DIK',
        'PATSUS',
        'IZIN',
        'CUTI',
        'SKT',
        'TK',
    ];

    /**
     * Status yang boleh dipilih saat SCAN QR (di kantor).
     */
    private const QR_ALLOWED_STATUSES = ['H', 'D'];

    /**
     * Status yang boleh dipilih saat MANUAL (dari rumah).
     */
    private const MANUAL_ALLOWED_STATUSES = [
        'LD',
        'P',
        'DIKSIP',
        'DIK',
        'PATSUS',
        'IZIN',
        'CUTI',
        'SKT',
    ];

    private AttendanceRepository $attendanceRepository;
    private AttendanceStatusRepository $statusRepository;
    private AttendanceQrRepository $qrRepository;
    private UserRepository $userRepository;
    private ProfileRepository $profileRepository;

    public function __construct(
        AttendanceRepository $attendanceRepository,
        AttendanceStatusRepository $statusRepository,
        AttendanceQrRepository $qrRepository,
        UserRepository $userRepository,
        ProfileRepository $profileRepository
    ) {
        $this->attendanceRepository = $attendanceRepository;
        $this->statusRepository = $statusRepository;
        $this->qrRepository = $qrRepository;
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    // ============================================================
    // SCAN — check-in
    // ============================================================

    public function checkIn(AttendanceCheckInRequest $request): AttendanceResponse
    {
        $this->validateUser($request->userId);

        // 1. QR valid?
        if ($request->qrCode === null || trim($request->qrCode) === '') {
            throw new Exception('QR Code tidak boleh kosong.');
        }

        if (!$this->qrRepository->existsByCode($request->qrCode)) {
            throw new Exception('QR Code tidak valid atau tidak aktif.');
        }

        // 2. Status valid?
        if ($request->statusCode === null || !in_array($request->statusCode, self::QR_ALLOWED_STATUSES, true)) {
            throw new Exception('Status tidak valid. Pilih Hadir atau Dinas.');
        }

        if (!$this->statusRepository->existsByCode($request->statusCode)) {
            throw new Exception('Status tidak dikenali.');
        }

        // 3. Sudah ada absen hari ini?
        $today = new DateTimeImmutable('today');
        $existing = $this->attendanceRepository->findByUserAndDate($request->userId, $today);

        if ($existing !== null) {
            throw new Exception(
                'Anda sudah memiliki absensi hari ini (' . $existing->statusCode . ').'
            );
        }

        // 4. Belum ada absen → buat baru
        $attendance = new Attendance();
        $attendance->userId = $request->userId;
        $attendance->attendanceDate = $today;
        $attendance->checkInTime = new DateTimeImmutable();
        $attendance->checkInQr = $request->qrCode;
        $attendance->statusCode = $request->statusCode;
        $attendance->remarks = $request->remarks;
        $attendance->createdBy = $request->userId;

        $saved = $this->attendanceRepository->save($attendance);

        return $this->buildResponse($saved, 'check_in', 'Absen masuk berhasil.');
    }

    // ============================================================
    // SCAN — check-out
    // ============================================================

    public function checkOut(AttendanceCheckOutRequest $request): AttendanceResponse
    {
        $this->validateUser($request->userId);

        if ($request->qrCode === null || trim($request->qrCode) === '') {
            throw new Exception('QR Code tidak boleh kosong.');
        }

        if (!$this->qrRepository->existsByCode($request->qrCode)) {
            throw new Exception('QR Code tidak valid atau tidak aktif.');
        }

        $today = new DateTimeImmutable('today');
        $existing = $this->attendanceRepository->findByUserAndDate($request->userId, $today);

        if ($existing === null) {
            throw new Exception('Anda belum melakukan absen masuk hari ini.');
        }

        if ($existing->checkInTime === null) {
            throw new Exception('Anda belum melakukan absen masuk hari ini.');
        }

        if ($existing->checkOutTime !== null) {
            throw new Exception('Anda sudah melakukan absen keluar hari ini.');
        }

        $existing->checkOutTime = new DateTimeImmutable();
        $existing->checkOutQr = $request->qrCode;

        $this->attendanceRepository->update($existing);

        return $this->buildResponse($existing, 'check_out', 'Absen keluar berhasil.');
    }

    // ============================================================
    // MANUAL — dari rumah
    // ============================================================

    public function manual(AttendanceManualRequest $request): AttendanceResponse
    {
        $this->validateUser($request->userId);

        if (!in_array($request->statusCode, self::MANUAL_ALLOWED_STATUSES, true)) {
            throw new Exception('Status tidak valid untuk input manual.');
        }

        if (!$this->statusRepository->existsByCode($request->statusCode)) {
            throw new Exception('Status tidak dikenali.');
        }

        // Tanggal — default hari ini, max H-1
        $date = $request->date !== null
            ? new DateTimeImmutable($request->date)
            : new DateTimeImmutable('today');

        $today = new DateTimeImmutable('today');
        $minDate = $today->modify('-1 day');

        if ($date > $today) {
            throw new Exception('Tidak bisa input absen untuk tanggal yang akan datang.');
        }

        if ($date < $minDate) {
            throw new Exception('Input manual hanya untuk hari ini atau kemarin.');
        }

        // Sudah ada absen di tanggal itu?
        $existing = $this->attendanceRepository->findByUserAndDate($request->userId, $date);

        if ($existing !== null) {
            throw new Exception(
                'Anda sudah memiliki absensi hari ini (' . $existing->statusCode . ').'
            );
        }

        // Belum ada absen → buat baru
        $attendance = new Attendance();
        $attendance->userId = $request->userId;
        $attendance->attendanceDate = $date;
        $attendance->statusCode = $request->statusCode;
        $attendance->remarks = $request->remarks;
        $attendance->createdBy = $request->userId;

        $saved = $this->attendanceRepository->save($attendance);

        return $this->buildResponse($saved, 'manual', 'Absen manual berhasil disimpan.');
    }

    // ============================================================
    // GET TODAY STATUS — untuk UI
    // ============================================================

    /**
     * Return state absen user hari ini.
     * @return array{state: string, attendance: ?Attendance}
     *   state: 'none' | 'checked_in' | 'complete'
     */
    /**
     * Return state absen user hari ini.
     * @return array{state: string, attendance: ?Attendance}
     *   state: 'none' | 'checked_in' | 'complete' | 'final'
     */
    public function getTodayStatus(int $userId): array
    {
        $today = new DateTimeImmutable('today');
        $attendance = $this->attendanceRepository->findByUserAndDate($userId, $today);

        if ($attendance === null) {
            return ['state' => 'none', 'attendance' => null];
        }

        // Single-scan (manual: LD, P, DIKSIP, DIK, PATSUS, IZIN, CUTI, SKT, TK)
        if (in_array($attendance->statusCode, self::SINGLE_SCAN_STATUSES, true)) {
            return ['state' => 'final', 'attendance' => $attendance];
        }

        // Multi-scan (H, D)
        if ($attendance->checkOutTime === null) {
            return ['state' => 'checked_in', 'attendance' => $attendance];
        }

        return ['state' => 'complete', 'attendance' => $attendance];
    }

    // ============================================================
    // AUTO-TK — ensure attendance for date
    // ============================================================

    /**
     * Untuk tanggal tertentu (Senin-Jumat):
     * - Ambil semua user aktif (role user)
     * - Cari user yang belum absen
     * - Isi otomatis dengan status TK
     *
     * Return: jumlah baris yang dibuat.
     */
    public function ensureAttendanceForDate(DateTimeImmutable $date): int
    {
        // Normalisasi
        $date = $date->setTime(0, 0, 0);

        // 1. Skip Sabtu/Minggu
        $dow = (int) $date->format('N');
        if ($dow > 5) {
            return 0;
        }

        $today = (new DateTimeImmutable('today'))->setTime(0, 0, 0);

        // 2. Skip tanggal yang akan datang
        if ($date > $today) {
            return 0;
        }

        // 3. Skip HARI INI (user masih punya waktu absen)
        if ($date == $today) {
            return 0;
        }

        // 4. ✅ Skip tanggal SEBELUM sistem aktif
        $startDate = new DateTimeImmutable(self::ATTENDANCE_START_DATE);
        if ($date < $startDate) {
            return 0;
        }

        // 5. Ambil user & yang sudah absen
        $users = $this->userRepository->findAll();
        $alreadyAbsen = $this->attendanceRepository->findUserIdsByDate($date);
        $alreadyAbsenMap = array_flip($alreadyAbsen);

        $created = 0;

        foreach ($users as $user) {
            if ($user->role !== \Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole::USER) {
                continue;
            }

            if (isset($alreadyAbsenMap[$user->id])) {
                continue;
            }

            $attendance = new Attendance();
            $attendance->userId = $user->id;
            $attendance->attendanceDate = $date;
            $attendance->statusCode = 'TK';
            $attendance->remarks = 'Auto-generated: tidak absen';

            $this->attendanceRepository->save($attendance);
            $created++;
        }

        return $created;
    }

    // ============================================================
    // REKAP HARIAN
    // ============================================================

    /**
     * Return data rekap harian — untuk ditampilkan di tabel.
     * Auto-ensure TK dulu.
     *
     * @return array{
     *   date: DateTimeImmutable,
     *   rows: array<array{user, profile, attendance}>,
     *   summary: array<string,int>
     * }
     */
    public function getRekapHarian(DateTimeImmutable $date): array
    {
        // Auto-TK dulu
        $this->ensureAttendanceForDate($date);

        $users = $this->userRepository->findAll();
        $attendances = $this->attendanceRepository->findByDate($date);

        // Index attendances by user_id
        $attMap = [];
        foreach ($attendances as $a) {
            $attMap[$a->userId] = $a;
        }

        $rows = [];
        $summary = [
            'H' => 0,
            'D' => 0,
            'P' => 0,
            'DIKSIP' => 0,
            'DIK' => 0,
            'PATSUS' => 0,
            'LD' => 0,
            'IZIN' => 0,
            'CUTI' => 0,
            'SKT' => 0,
            'TK' => 0,
            'total_user' => 0,
            'total_present' => 0,
        ];

        foreach ($users as $user) {
            if ($user->role !== \Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole::USER) {
                continue;
            }

            $profile = $this->profileRepository->findByUserId($user->id);
            $attendance = $attMap[$user->id] ?? null;

            $rows[] = [
                'user' => $user,
                'profile' => $profile,
                'attendance' => $attendance,
            ];

            $summary['total_user']++;

            // ✅ Hitung user yang absen (bukan TK)
            if ($attendance !== null && $attendance->statusCode !== 'TK') {
                $summary['total_present']++;
            }

            if ($attendance !== null && isset($summary[$attendance->statusCode])) {
                $summary[$attendance->statusCode]++;
            }
        }

        return [
            'date' => $date,
            'rows' => $rows,
            'summary' => $summary,
        ];
    }

    // ============================================================
    // REKAP BULANAN
    // ============================================================

    /**
     * Return rekap per user dalam rentang tanggal.
     *
     * @return array{
     *   start: DateTimeImmutable,
     *   end: DateTimeImmutable,
     *   rows: array<array{user, profile, counts: array<string,int>}>
     * }
     */
    public function getRekapBulanan(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $users = $this->userRepository->findAll();
        $countsByUser = $this->attendanceRepository->countByStatusInRange($start, $end);

        $rows = [];
        foreach ($users as $user) {
            if ($user->role !== \Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole::USER) {
                continue;
            }

            $profile = $this->profileRepository->findByUserId($user->id);
            $counts = $countsByUser[$user->id] ?? [];

            $rows[] = [
                'user' => $user,
                'profile' => $profile,
                'counts' => $counts,
            ];
        }

        return [
            'start' => $start,
            'end' => $end,
            'rows' => $rows,
        ];
    }

    public function getStartDate(): DateTimeImmutable
    {
        return new DateTimeImmutable(self::ATTENDANCE_START_DATE);
    }

    // ============================================================
    // HELPERS
    // ============================================================

    private function validateUser(int $userId): void
    {
        if ($userId <= 0) {
            throw new Exception('User tidak valid.');
        }

        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            throw new Exception('User tidak ditemukan.');
        }
    }

    private function buildResponse(Attendance $attendance, string $action, string $message): AttendanceResponse
    {
        $response = new AttendanceResponse();
        $response->attendance = $attendance;
        $response->action = $action;
        $response->message = $message;
        return $response;
    }
}