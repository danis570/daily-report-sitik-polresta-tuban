<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceCheckInRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceCheckOutRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance\AttendanceManualRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceQrRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\AttendanceStatusRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class AttendanceServiceTest extends TestCase
{
    private AttendanceService $service;
    private AttendanceRepository $attendanceRepository;
    private UserRepository $userRepository;
    private ProfileRepository $profileRepository;

    private int $userId;
    private const QR = 'ABSEN-SITIK-TUBAN';

    protected function setUp(): void
    {
        Database::clearConnection();
        $pdo = Database::getConnection();

        $this->attendanceRepository = new AttendanceRepository($pdo);
        $this->userRepository = new UserRepository($pdo);
        $this->profileRepository = new ProfileRepository($pdo);

        $statusRepo = new AttendanceStatusRepository($pdo);
        $qrRepo = new AttendanceQrRepository($pdo);

        $this->service = new AttendanceService(
            $this->attendanceRepository,
            $statusRepo,
            $qrRepo,
            $this->userRepository,
            $this->profileRepository
        );

        // Cleanup
        $this->attendanceRepository->deleteAll();
        $this->profileRepository->deleteAll();
        $this->userRepository->deleteAll();

        // Seed user
        $user = new User();
        $user->email = 'att-service@test.com';
        $user->password = 'password123';
        $user->role = UserRole::USER;
        $saved = $this->userRepository->save($user);
        $this->userId = $saved->id;
    }

    // ============================================================
    // CHECK-IN
    // ============================================================

    public function testCheckInSuccess(): void
    {
        $req = new AttendanceCheckInRequest();
        $req->userId = $this->userId;
        $req->qrCode = self::QR;
        $req->statusCode = 'H';

        $response = $this->service->checkIn($req);

        self::assertSame('check_in', $response->action);
        self::assertSame('H', $response->attendance->statusCode);
        self::assertNotNull($response->attendance->checkInTime);
        self::assertNull($response->attendance->checkOutTime);
    }

    public function testCheckInWithDinasStatus(): void
    {
        $req = new AttendanceCheckInRequest();
        $req->userId = $this->userId;
        $req->qrCode = self::QR;
        $req->statusCode = 'D';

        $response = $this->service->checkIn($req);

        self::assertSame('D', $response->attendance->statusCode);
    }

    public function testCheckInFailsInvalidQr(): void
    {
        $req = new AttendanceCheckInRequest();
        $req->userId = $this->userId;
        $req->qrCode = 'QR-PALSU';
        $req->statusCode = 'H';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('QR Code tidak valid');

        $this->service->checkIn($req);
    }

    public function testCheckInFailsInvalidStatus(): void
    {
        $req = new AttendanceCheckInRequest();
        $req->userId = $this->userId;
        $req->qrCode = self::QR;
        $req->statusCode = 'CUTI';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Status tidak valid');

        $this->service->checkIn($req);
    }

    public function testCheckInFailsAlreadyCheckedIn(): void
    {
        $req = new AttendanceCheckInRequest();
        $req->userId = $this->userId;
        $req->qrCode = self::QR;
        $req->statusCode = 'H';
        $this->service->checkIn($req);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah memiliki absensi');

        $this->service->checkIn($req);
    }

    // ============================================================
    // CHECK-OUT
    // ============================================================

    public function testCheckOutSuccess(): void
    {
        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';
        $this->service->checkIn($in);

        $out = new AttendanceCheckOutRequest();
        $out->userId = $this->userId;
        $out->qrCode = self::QR;

        $response = $this->service->checkOut($out);

        self::assertSame('check_out', $response->action);
        self::assertNotNull($response->attendance->checkOutTime);
    }

    public function testCheckOutFailsNotCheckedIn(): void
    {
        $out = new AttendanceCheckOutRequest();
        $out->userId = $this->userId;
        $out->qrCode = self::QR;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('belum melakukan absen masuk');

        $this->service->checkOut($out);
    }

    public function testCheckOutFailsAlreadyCheckedOut(): void
    {
        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';
        $this->service->checkIn($in);

        $out = new AttendanceCheckOutRequest();
        $out->userId = $this->userId;
        $out->qrCode = self::QR;
        $this->service->checkOut($out);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah melakukan absen keluar');

        $this->service->checkOut($out);
    }

    // ============================================================
    // MANUAL
    // ============================================================

    public function testManualIzinSuccess(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'IZIN';
        $req->remarks = 'Ada urusan keluarga';

        $response = $this->service->manual($req);

        self::assertSame('manual', $response->action);
        self::assertSame('IZIN', $response->attendance->statusCode);
        self::assertNull($response->attendance->checkInTime);
    }

    public function testManualFailsInvalidStatus(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'H';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Status tidak valid');

        $this->service->manual($req);
    }

    public function testManualFailsAlreadyExists(): void
    {
        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';
        $this->service->checkIn($in);

        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'IZIN';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah memiliki absensi');

        $this->service->manual($req);
    }

    public function testManualFailsTooFarBack(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'IZIN';
        $req->date = (new DateTimeImmutable('today'))->modify('-3 days')->format('Y-m-d');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('hari ini atau kemarin');

        $this->service->manual($req);
    }

    public function testManualFailsFutureDate(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'IZIN';
        $req->date = (new DateTimeImmutable('today'))->modify('+1 day')->format('Y-m-d');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('tanggal yang akan datang');

        $this->service->manual($req);
    }

    // ============================================================
    // TODAY STATUS
    // ============================================================

    public function testGetTodayStatusNone(): void
    {
        $result = $this->service->getTodayStatus($this->userId);

        self::assertSame('none', $result['state']);
        self::assertNull($result['attendance']);
    }

    public function testGetTodayStatusCheckedIn(): void
    {
        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';
        $this->service->checkIn($in);

        $result = $this->service->getTodayStatus($this->userId);

        self::assertSame('checked_in', $result['state']);
        self::assertNotNull($result['attendance']);
    }

    public function testGetTodayStatusComplete(): void
    {
        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';
        $this->service->checkIn($in);

        $out = new AttendanceCheckOutRequest();
        $out->userId = $this->userId;
        $out->qrCode = self::QR;
        $this->service->checkOut($out);

        $result = $this->service->getTodayStatus($this->userId);

        self::assertSame('complete', $result['state']);
    }

    // ============================================================
    // AUTO-TK
    // ============================================================
public function testEnsureAttendanceSkipsBeforeCutoff(): void
{
    $startDate = $this->service->getStartDate();

    // Cari tanggal sebelum cutoff, mis. 7 hari sebelumnya
    $beforeCutoff = $startDate->modify('-7 days');

    // Kalau hari itu weekend, maju ke Senin
    while ((int) $beforeCutoff->format('N') > 5) {
        $beforeCutoff = $beforeCutoff->modify('+1 day');
    }

    $created = $this->service->ensureAttendanceForDate($beforeCutoff);

    self::assertSame(0, $created, 'Auto-TK harus di-skip sebelum cutoff.');

    $found = $this->attendanceRepository->findByUserAndDate($this->userId, $beforeCutoff);
    self::assertNull($found);
}
   public function testEnsureAttendanceCreatesTkForMissingUsers(): void
{
    // 1. Ambil cutoff date dari Service
    $startDate = $this->service->getStartDate();
    $today     = new DateTimeImmutable('today');

    // 2. Cari hari kerja setelah cutoff & sebelum hari ini
    $date = $startDate;

    // Kalau cutoff = hari ini, maju 1 hari dulu
    if ($date == $today) {
        $date = $date->modify('+1 day');
    }

    // Loop sampai ketemu hari kerja (Senin–Jumat) & sebelum hari ini
    $maxIterasi = 14;   // cari maks 2 minggu
    $found = false;

    for ($i = 0; $i < $maxIterasi; $i++) {
        $dow = (int) $date->format('N');
        if ($dow <= 5 && $date < $today) {
            $found = true;
            break;
        }
        $date = $date->modify('+1 day');
    }

    if (!$found) {
        self::markTestSkipped(
            'Tidak ada hari kerja valid antara cutoff (' . $startDate->format('Y-m-d') . ') dan hari ini (' . $today->format('Y-m-d') . ').'
        );
    }

    // 3. Hapus existing kalau ada (biar deterministik)
    $existing = $this->attendanceRepository->findByUserAndDate($this->userId, $date);
    if ($existing !== null) {
        $this->attendanceRepository->deleteById($existing->id);
    }

    // 4. Jalankan auto-TK
    $created = $this->service->ensureAttendanceForDate($date);

    // 5. Assert
    self::assertSame(1, $created, "Gagal auto-TK di tanggal {$date->format('Y-m-d (l)')}");

    $found = $this->attendanceRepository->findByUserAndDate($this->userId, $date);
    self::assertNotNull($found);
    self::assertSame('TK', $found->statusCode);
}

    public function testEnsureAttendanceSkipsWeekend(): void
    {
        $sat = (new DateTimeImmutable('today'))->modify('next saturday');

        $created = $this->service->ensureAttendanceForDate($sat);

        self::assertSame(0, $created);
    }

    public function testEnsureAttendanceSkipsExistingAttendance(): void
    {
        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';
        $this->service->checkIn($in);

        $today = new DateTimeImmutable('today');
        $dow = (int) $today->format('N');
        if ($dow > 5) {
            self::markTestSkipped('Hari ini weekend.');
        }

        $created = $this->service->ensureAttendanceForDate($today);

        self::assertSame(0, $created);
    }

    public function testEnsureAttendanceSkipsToday(): void
    {
        $today = new DateTimeImmutable('today');

        $dow = (int) $today->format('N');
        if ($dow > 5) {
            self::markTestSkipped('Hari ini weekend.');
        }

        $created = $this->service->ensureAttendanceForDate($today);

        self::assertSame(0, $created, 'Auto-TK untuk hari ini harus di-skip.');

        $found = $this->attendanceRepository->findByUserAndDate($this->userId, $today);
        self::assertNull($found, 'User tidak boleh di-auto-TK hari ini.');
    }

    // ============================================================
    // REKAP HARIAN
    // ============================================================

    public function testGetRekapHarian(): void
    {
        $today = new DateTimeImmutable('today');
        $dow = (int) $today->format('N');
        if ($dow > 5) {
            self::markTestSkipped('Hari ini weekend.');
        }

        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';
        $this->service->checkIn($in);

        $rekap = $this->service->getRekapHarian($today);

        self::assertSame($today->format('Y-m-d'), $rekap['date']->format('Y-m-d'));
        self::assertNotEmpty($rekap['rows']);
        self::assertSame(1, $rekap['summary']['H']);
        self::assertSame(1, $rekap['summary']['total_user']);
    }

    // ============================================================
    // STATUS BARU — single-scan (LD, P, DIKSIP, DIK, PATSUS)
    // ============================================================

    public function testManualLdSuccess(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'LD';
        $req->remarks = 'Lepas dinas';

        $response = $this->service->manual($req);

        self::assertSame('manual', $response->action);
        self::assertSame('LD', $response->attendance->statusCode);
        self::assertNull($response->attendance->checkInTime);
        self::assertNull($response->attendance->checkOutTime);
    }

    public function testManualPawasSuccess(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'P';

        $response = $this->service->manual($req);

        self::assertSame('P', $response->attendance->statusCode);
    }

    public function testManualDiksipSuccess(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'DIKSIP';

        $response = $this->service->manual($req);

        self::assertSame('DIKSIP', $response->attendance->statusCode);
    }

    public function testManualDikSuccess(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'DIK';

        $response = $this->service->manual($req);

        self::assertSame('DIK', $response->attendance->statusCode);
    }

    public function testManualPatsusSuccess(): void
    {
        $req = new AttendanceManualRequest();
        $req->userId = $this->userId;
        $req->statusCode = 'PATSUS';

        $response = $this->service->manual($req);

        self::assertSame('PATSUS', $response->attendance->statusCode);
    }

    public function testManualLdThenCheckInFails(): void
    {
        $manual = new AttendanceManualRequest();
        $manual->userId = $this->userId;
        $manual->statusCode = 'LD';
        $this->service->manual($manual);

        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah memiliki absensi');

        $this->service->checkIn($in);
    }

    public function testManualIzinThenCheckInFails(): void
    {
        $manual = new AttendanceManualRequest();
        $manual->userId = $this->userId;
        $manual->statusCode = 'IZIN';
        $this->service->manual($manual);

        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah memiliki absensi');

        $this->service->checkIn($in);
    }

    // ============================================================
    // TODAY STATUS — single-scan state
    // ============================================================

    public function testGetTodayStatusFinalForLd(): void
    {
        $manual = new AttendanceManualRequest();
        $manual->userId = $this->userId;
        $manual->statusCode = 'LD';
        $this->service->manual($manual);

        $status = $this->service->getTodayStatus($this->userId);

        self::assertSame('final', $status['state']);
        self::assertSame('LD', $status['attendance']->statusCode);
    }

    public function testGetTodayStatusFinalForPawas(): void
    {
        $manual = new AttendanceManualRequest();
        $manual->userId = $this->userId;
        $manual->statusCode = 'P';
        $this->service->manual($manual);

        $status = $this->service->getTodayStatus($this->userId);

        self::assertSame('final', $status['state']);
    }

    public function testGetTodayStatusFinalForIzin(): void
    {
        $manual = new AttendanceManualRequest();
        $manual->userId = $this->userId;
        $manual->statusCode = 'IZIN';
        $this->service->manual($manual);

        $status = $this->service->getTodayStatus($this->userId);

        self::assertSame('final', $status['state']);
    }

    // ============================================================
    // CHECK-IN dengan status final existing — semua harus ditolak
    // ============================================================

    public function testCheckInFailsAfterManualPawas(): void
    {
        $manual = new AttendanceManualRequest();
        $manual->userId = $this->userId;
        $manual->statusCode = 'P';
        $this->service->manual($manual);

        $in = new AttendanceCheckInRequest();
        $in->userId = $this->userId;
        $in->qrCode = self::QR;
        $in->statusCode = 'H';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah memiliki absensi');

        $this->service->checkIn($in);
    }

    public function testManualFailsAfterManualLdExists(): void
    {
        $first = new AttendanceManualRequest();
        $first->userId = $this->userId;
        $first->statusCode = 'LD';
        $this->service->manual($first);

        $second = new AttendanceManualRequest();
        $second->userId = $this->userId;
        $second->statusCode = 'IZIN';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('sudah memiliki absensi');

        $this->service->manual($second);
    }
}