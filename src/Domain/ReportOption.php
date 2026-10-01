<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Domain;

use DateTimeImmutable;

class ReportOption
{
    public ?int $id = null;

    public string $category;

    public string $name;

    public ?string $description = null;

    public DateTimeImmutable $createdAt;

    public ?DateTimeImmutable $updatedAt = null;

    public ?DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
