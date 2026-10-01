<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportItem;

class ReportItemRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(ReportItem $reportItem): ReportItem
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO report_items (
                report_id,
                item_no,
                target_option_id,
                activity_option_id,
                personnel_strength_option_id,
                location_option_id,
                person_in_charge_option_id,
                expected_result_option_id,
                remarks
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $reportItem->reportId,
            $reportItem->itemNo,
            $reportItem->targetOptionId,
            $reportItem->activityOptionId,
            $reportItem->personnelStrengthOptionId,
            $reportItem->locationOptionId,
            $reportItem->personInChargeOptionId,
            $reportItem->expectedResultOptionId,
            $reportItem->remarks,
        ]);

        $reportItem->id = (int) $this->pdo->lastInsertId();

        return $reportItem;
    }

    public function findById(int $id): ?ReportItem
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM report_items
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->mapRowToReportItem($result);
    }

    public function findByReportId(int $reportId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM report_items
            WHERE report_id = ?
            ORDER BY item_no ASC
        ");

        $stmt->execute([$reportId]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            return [];
        }

        $reportItems = [];

        foreach ($results as $result) {
            $reportItems[] = $this->mapRowToReportItem($result);
        }

        return $reportItems;
    }

    public function countByReportId(int $reportId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM report_items
            WHERE report_id = ?
        ");

        $stmt->execute([$reportId]);

        return (int) $stmt->fetchColumn();
    }

    public function update(ReportItem $reportItem): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE report_items
            SET
                report_id = ?,
                item_no = ?,
                target_option_id = ?,
                activity_option_id = ?,
                personnel_strength_option_id = ?,
                location_option_id = ?,
                person_in_charge_option_id = ?,
                expected_result_option_id = ?,
                remarks = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $reportItem->reportId,
            $reportItem->itemNo,
            $reportItem->targetOptionId,
            $reportItem->activityOptionId,
            $reportItem->personnelStrengthOptionId,
            $reportItem->locationOptionId,
            $reportItem->personInChargeOptionId,
            $reportItem->expectedResultOptionId,
            $reportItem->remarks,
            $reportItem->id,
        ]);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM report_items
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("
            DELETE FROM report_items
        ");
    }

    private function mapRowToReportItem(array $result): ReportItem
    {
        $reportItem = new ReportItem();

        $reportItem->id = (int) $result['id'];
        $reportItem->reportId = (int) $result['report_id'];
        $reportItem->itemNo = (int) $result['item_no'];

        $reportItem->targetOptionId = (int) $result['target_option_id'];
        $reportItem->activityOptionId = (int) $result['activity_option_id'];
        $reportItem->personnelStrengthOptionId =
            (int) $result['personnel_strength_option_id'];
        $reportItem->locationOptionId =
            (int) $result['location_option_id'];
        $reportItem->personInChargeOptionId =
            (int) $result['person_in_charge_option_id'];
        $reportItem->expectedResultOptionId =
            (int) $result['expected_result_option_id'];

        $reportItem->remarks = $result['remarks'];

        $reportItem->createdAt = new DateTimeImmutable(
            $result['created_at']
        );

        $reportItem->updatedAt = $result['updated_at']
            ? new DateTimeImmutable($result['updated_at'])
            : null;

        $reportItem->deletedAt = $result['deleted_at']
            ? new DateTimeImmutable($result['deleted_at'])
            : null;

        return $reportItem;
    }
}