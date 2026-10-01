<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;

class ReportOptionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(ReportOption $reportOption): ReportOption
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO report_options (
                category,
                name,
                description
            )
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $reportOption->category,
            $reportOption->name,
            $reportOption->description,
        ]);

        $reportOption->id = (int) $this->pdo->lastInsertId();

        return $reportOption;
    }

    public function findById(int $id): ?ReportOption
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM report_options
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->mapRowToReportOption($result);
    }

    public function findByCategory(string $category): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM report_options
            WHERE category = ?
            ORDER BY name ASC
        ");

        $stmt->execute([$category]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            return [];
        }

        $reportOptions = [];

        foreach ($results as $result) {
            $reportOptions[] = $this->mapRowToReportOption($result);
        }

        return $reportOptions;
    }

    public function findByCategoryAndName(
        string $category,
        string $name
    ): ?ReportOption {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM report_options
            WHERE category = ?
            AND name = ?
        ");

        $stmt->execute([
            $category,
            $name,
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->mapRowToReportOption($result);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM report_options
            ORDER BY category ASC, name ASC
        ");

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            return [];
        }

        $reportOptions = [];

        foreach ($results as $result) {
            $reportOptions[] = $this->mapRowToReportOption($result);
        }

        return $reportOptions;
    }

    public function countAll(): int
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*)
            FROM report_options
        ");

        return (int) $stmt->fetchColumn();
    }

    public function update(ReportOption $reportOption): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE report_options
            SET
                category = ?,
                name = ?,
                description = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $reportOption->category,
            $reportOption->name,
            $reportOption->description,
            $reportOption->id,
        ]);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM report_options
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("
            DELETE FROM report_options
        ");
    }

    private function mapRowToReportOption(array $result): ReportOption
    {
        $reportOption = new ReportOption();

        $reportOption->id = (int) $result['id'];
        $reportOption->category = $result['category'];
        $reportOption->name = $result['name'];
        $reportOption->description = $result['description'];

        $reportOption->createdAt = new DateTimeImmutable(
            $result['created_at']
        );

        $reportOption->updatedAt = $result['updated_at']
            ? new DateTimeImmutable($result['updated_at'])
            : null;

        $reportOption->deletedAt = $result['deleted_at']
            ? new DateTimeImmutable($result['deleted_at'])
            : null;

        return $reportOption;
    }
}