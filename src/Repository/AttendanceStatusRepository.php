<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\AttendanceStatus;

class AttendanceStatusRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Simpan status baru.
     */
    public function save(AttendanceStatus $status): AttendanceStatus
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO attendance_statuses (code, label, sort_order, is_active)
        VALUES (?, ?, ?, ?)
    ");
        $stmt->execute([
            $status->code,
            $status->label,
            $status->sortOrder,
            $status->isActive ? 1 : 0,
        ]);

        return $status;
    }

    /**
     * Update status.
     */
    public function update(AttendanceStatus $status): bool
    {
        $stmt = $this->pdo->prepare("
        UPDATE attendance_statuses
        SET code = ?, label = ?, sort_order = ?, is_active = ?
        WHERE code = ?
    ");
        return $stmt->execute([
            $status->code,
            $status->label,
            $status->sortOrder,
            $status->isActive ? 1 : 0,
            $status->code,   // WHERE pakai code (PK)
        ]);
    }

    /**
     * Hapus status.
     */
    public function delete(string $code): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM attendance_statuses WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Hitung berapa kali status ini dipakai di attendances.
     */
    public function countUsage(string $code): int
    {
        $stmt = $this->pdo->prepare("
        SELECT COUNT(*) FROM attendances WHERE status_code = ?
    ");
        $stmt->execute([$code]);
        return (int) $stmt->fetchColumn();
    }

    /**
 * Untuk dropdown absensi — hanya status aktif.
 */
public function findAllActive(): array
{
    $stmt = $this->pdo->query("
        SELECT * FROM attendance_statuses
        WHERE is_active = TRUE
        ORDER BY sort_order ASC
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($rows as $row) {
        $result[] = $this->mapRowToStatus($row);
    }
    return $result;
}

/**
 * Untuk CRUD admin — tampilkan semua (aktif + nonaktif).
 */
public function findAll(): array
{
    $stmt = $this->pdo->query("
        SELECT * FROM attendance_statuses
        ORDER BY sort_order ASC
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($rows as $row) {
        $result[] = $this->mapRowToStatus($row);
    }
    return $result;
}

    public function findByCode(string $code): ?AttendanceStatus
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM attendance_statuses WHERE code = ?
        ");
        $stmt->execute([$code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToStatus($row) : null;
    }

    /**
     * Cek apakah kode status valid & aktif.
     */
    public function existsByCode(string $code): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM attendance_statuses
            WHERE code = ? AND is_active = TRUE
        ");
        $stmt->execute([$code]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function mapRowToStatus(array $row): AttendanceStatus
    {
        $status = new AttendanceStatus();
        $status->code = $row['code'];
        $status->label = $row['label'];
        $status->sortOrder = (int) $row['sort_order'];
        $status->isActive = (bool) $row['is_active'];

        return $status;
    }
}