<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Attendance;

class AttendanceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Attendance $attendance): Attendance
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO attendances (
                user_id, attendance_date,
                check_in_time, check_out_time,
                check_in_qr, check_out_qr,
                status_code, remarks, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $attendance->userId,
            $attendance->attendanceDate->format('Y-m-d'),
            $attendance->checkInTime?->format('Y-m-d H:i:s'),
            $attendance->checkOutTime?->format('Y-m-d H:i:s'),
            $attendance->checkInQr,
            $attendance->checkOutQr,
            $attendance->statusCode,
            $attendance->remarks,
            $attendance->createdBy,
        ]);

        $attendance->id = (int) $this->pdo->lastInsertId();
        return $attendance;
    }

    public function update(Attendance $attendance): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE attendances SET
                check_in_time  = ?,
                check_out_time = ?,
                check_in_qr    = ?,
                check_out_qr   = ?,
                status_code    = ?,
                remarks        = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $attendance->checkInTime?->format('Y-m-d H:i:s'),
            $attendance->checkOutTime?->format('Y-m-d H:i:s'),
            $attendance->checkInQr,
            $attendance->checkOutQr,
            $attendance->statusCode,
            $attendance->remarks,
            $attendance->id,
        ]);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM attendances WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function findById(int $id): ?Attendance
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attendances WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToAttendance($row) : null;
    }

    public function findByUserAndDate(int $userId, DateTimeImmutable $date): ?Attendance
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM attendances
            WHERE user_id = ? AND attendance_date = ?
        ");
        $stmt->execute([$userId, $date->format('Y-m-d')]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToAttendance($row) : null;
    }

    /**
     * Cari semua absensi di tanggal tertentu.
     */
    public function findByDate(DateTimeImmutable $date): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM attendances
            WHERE attendance_date = ?
        ");
        $stmt->execute([$date->format('Y-m-d')]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapRowToAttendance($row);
        }
        return $result;
    }

    /**
     * Ambil semua user_id yang sudah absen di tanggal tertentu.
     * Dipakai untuk auto-TK: cari user yang BELUM absen.
     *
     * @return array<int> daftar user_id
     */
    public function findUserIdsByDate(DateTimeImmutable $date): array
    {
        $stmt = $this->pdo->prepare("
            SELECT user_id FROM attendances
            WHERE attendance_date = ?
        ");
        $stmt->execute([$date->format('Y-m-d')]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Rekap per status dalam rentang tanggal, group by user_id.
     * Return: [user_id => ['H' => 5, 'D' => 2, ...], ...]
     */
    public function countByStatusInRange(
        DateTimeImmutable $start,
        DateTimeImmutable $end
    ): array {
        $stmt = $this->pdo->prepare("
            SELECT user_id, status_code, COUNT(*) AS total
            FROM attendances
            WHERE attendance_date BETWEEN ? AND ?
            GROUP BY user_id, status_code
        ");
        $stmt->execute([
            $start->format('Y-m-d'),
            $end->format('Y-m-d'),
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $userId = (int) $row['user_id'];
            $status = $row['status_code'];
            $total = (int) $row['total'];

            if (!isset($result[$userId])) {
                $result[$userId] = [];
            }
            $result[$userId][$status] = $total;
        }

        return $result;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DELETE FROM attendances");
    }

    private function mapRowToAttendance(array $row): Attendance
    {
        $attendance = new Attendance();

        $attendance->id = (int) $row['id'];
        $attendance->userId = (int) $row['user_id'];
        $attendance->attendanceDate = new DateTimeImmutable($row['attendance_date']);

        $attendance->checkInTime = $row['check_in_time']
            ? new DateTimeImmutable($row['check_in_time'])
            : null;
        $attendance->checkOutTime = $row['check_out_time']
            ? new DateTimeImmutable($row['check_out_time'])
            : null;

        $attendance->checkInQr = $row['check_in_qr'];
        $attendance->checkOutQr = $row['check_out_qr'];
        $attendance->statusCode = $row['status_code'];
        $attendance->remarks = $row['remarks'];

        $attendance->createdAt = new DateTimeImmutable($row['created_at']);
        $attendance->updatedAt = new DateTimeImmutable($row['updated_at']);
        $attendance->createdBy = $row['created_by'] !== null
            ? (int) $row['created_by']
            : null;

        return $attendance;
    }
}