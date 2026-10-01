<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Report;

class ReportRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Report $report): Report
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reports (
                report_date,
                created_by
            )
            VALUES (?, ?)
        ");

        $stmt->execute([
            $report->reportDate->format('Y-m-d'),
            $report->createdBy,
        ]);

        $report->id = (int) $this->pdo->lastInsertId();

        return $report;
    }

    public function findById(int $id): ?Report
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM reports
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->mapRowToReport($result);
    }

    public function findByDate(DateTimeImmutable $date): ?Report
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM reports
            WHERE report_date = ?
        ");

        $stmt->execute([
            $date->format('Y-m-d')
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->mapRowToReport($result);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM reports
            ORDER BY report_date DESC
        ");

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return [];
        }

        $reports = [];

        foreach ($result as $row) {
            $reports[] = $this->mapRowToReport($row);
        }

        return $reports;
    }

    public function countAll(): int
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*)
            FROM reports
        ");

        return (int) $stmt->fetchColumn();
    }

    public function update(Report $report): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE reports
            SET report_date = ?,
                created_by = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $report->reportDate->format('Y-m-d'),
            $report->createdBy,
            $report->id,
        ]);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM reports
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DELETE FROM reports");
    }

    private function mapRowToReport(array $result): Report
    {
        $report = new Report();

        $report->id = (int) $result['id'];

        $report->reportDate = new DateTimeImmutable(
            $result['report_date']
        );

        $report->createdBy = $result['created_by'] !== null
            ? (int) $result['created_by']
            : null;

        $report->createdAt = new DateTimeImmutable(
            $result['created_at']
        );

        $report->updatedAt = $result['updated_at']
            ? new DateTimeImmutable($result['updated_at'])
            : null;

        $report->deletedAt = $result['deleted_at']
            ? new DateTimeImmutable($result['deleted_at'])
            : null;

        return $report;
    }
}