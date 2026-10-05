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
            created_by,
            created_at
        )
        VALUES (?, ?, ?)
    ");

        $stmt->execute([
            $report->reportDate->format('Y-m-d'),
            $report->createdBy,
            $report->createdAt?->format('Y-m-d H:i:s') ?? date('Y-m-d H:i:s'),
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

    public function countAll(): int
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*)
            FROM reports
        ");

        return (int) $stmt->fetchColumn();
    }

    public function findLatest(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM reports
            ORDER BY report_date DESC, id DESC
            LIMIT :limit
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

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

    public function countByDateRange(
        DateTimeImmutable $start,
        DateTimeImmutable $end
    ): int {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM reports
            WHERE report_date BETWEEN :start AND :end
        ");

        $stmt->execute([
            ':start' => $start->format('Y-m-d'),
            ':end' => $end->format('Y-m-d'),
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function findByDateRangeLatest(
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        int $limit = 10
    ): array {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM reports
            WHERE report_date BETWEEN :start AND :end
            ORDER BY report_date DESC, id DESC
            LIMIT :limit
        ");

        $stmt->bindValue(':start', $start->format('Y-m-d'));
        $stmt->bindValue(':end', $end->format('Y-m-d'));
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

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

    public function findByDateRange(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate
    ): array {
        $sql = "
            SELECT
                id,
                report_date,
                created_by,
                created_at,
                updated_at,
                deleted_at
            FROM reports
            WHERE report_date BETWEEN :start_date AND :end_date
            ORDER BY report_date DESC
        ";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ]);

        $reports = [];

        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $reports[] = $this->mapRowToReport($row);
        }

        return $reports;
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

    public function update(Report $report): bool
    {
        $stmt = $this->pdo->prepare("
        UPDATE reports
        SET
            report_date = ?,
            created_by = ?,
            created_at = ?,
            updated_at = NOW()
        WHERE id = ?
    ");

        return $stmt->execute([
            $report->reportDate->format('Y-m-d'),
            $report->createdBy,
            $report->createdAt?->format('Y-m-d H:i:s') ?? date('Y-m-d H:i:s'),
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