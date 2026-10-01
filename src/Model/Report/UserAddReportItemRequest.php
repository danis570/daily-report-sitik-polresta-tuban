<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Report;

class UserAddReportItemRequest
{
    public ?int $reportId = null;
    public ?int $targetOptionId = null;
    public ?int $activityOptionId = null;
    public ?int $personnelStrengthOptionId = null;
    public ?int $locationOptionId = null;
    public ?int $personInChargeOptionId = null;
    public ?int $expectedResultOptionId = null;
    public ?string $remarks = null;
}
