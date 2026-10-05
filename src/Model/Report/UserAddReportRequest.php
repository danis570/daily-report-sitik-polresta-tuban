<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Report;

use DateTimeImmutable;

class UserAddReportRequest
{
    public ?string $reportDate = null;
    public ?int $createdBy = null;
    public ?DateTimeImmutable $createdAt = null;
}