<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Domain;

use DateTimeImmutable;

class Report
{
    public ?int $id = null;
    public DateTimeImmutable $reportDate;
    public ?int $createdBy = null;

    public DateTimeImmutable $createdAt;
    public ?DateTimeImmutable $updatedAt = null;
    public ?DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
