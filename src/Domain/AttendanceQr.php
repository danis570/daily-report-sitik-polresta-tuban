<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Domain;

use DateTimeImmutable;

class AttendanceQr
{
    public ?int $id = null;
    public string $name;
    public string $code;
    public bool $isActive = true;
    public ?DateTimeImmutable $createdAt = null;
    public ?DateTimeImmutable $updatedAt = null;
}
