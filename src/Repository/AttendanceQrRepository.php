<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\AttendanceQr;

class AttendanceQrRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByCode(string $code): ?AttendanceQr
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM attendance_qr WHERE code = ?
        ");
        $stmt->execute([$code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToQr($row) : null;
    }

    public function existsByCode(string $code): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM attendance_qr
            WHERE code = ? AND is_active = TRUE
        ");
        $stmt->execute([$code]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function findActive(): array
    {
        $stmt = $this->pdo->query("
            SELECT * FROM attendance_qr WHERE is_active = TRUE
        ");

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapRowToQr($row);
        }
        return $result;
    }

    private function mapRowToQr(array $row): AttendanceQr
    {
        $qr = new AttendanceQr();
        $qr->id        = (int) $row['id'];
        $qr->name      = $row['name'];
        $qr->code      = $row['code'];
        $qr->isActive  = (bool) $row['is_active'];
        $qr->createdAt = new DateTimeImmutable($row['created_at']);
        $qr->updatedAt = new DateTimeImmutable($row['updated_at']);

        return $qr;
    }
}