<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Domain;

class AttendanceStatus
{
    public string $code;
    public string $label;
    public int $sortOrder = 0;
    public bool $isActive = true;
}
