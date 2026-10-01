<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Domain;

use DateTimeImmutable;

class ReportItem
{
    public ?int $id = null;

    public int $reportId;

    public int $itemNo;

    public int $targetOptionId;

    public int $activityOptionId;

    public int $personnelStrengthOptionId;

    public int $locationOptionId;

    public int $personInChargeOptionId;

    public int $expectedResultOptionId;

    public ?string $remarks = null;

    public DateTimeImmutable $createdAt;

    public ?DateTimeImmutable $updatedAt = null;

    public ?DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
}