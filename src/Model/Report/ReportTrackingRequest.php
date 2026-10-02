<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Report;

use DateTimeImmutable;

class ReportTrackingRequest
{
    public string $category;
    public int $optionId;
    public DateTimeImmutable $startDate;
    public DateTimeImmutable $endDate;
}