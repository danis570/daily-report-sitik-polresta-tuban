<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Domain;

use DateTimeImmutable;

class Attendance
{
    public ?int $id = null;
    public int $userId;
    public DateTimeImmutable $attendanceDate;

    // Waktu scan
    public ?DateTimeImmutable $checkInTime = null;
    public ?DateTimeImmutable $checkOutTime = null;

    // QR yang di-scan (audit)
    public ?string $checkInQr = null;
    public ?string $checkOutQr = null;

    // Status — default 'H'
    public string $statusCode = 'H';

    // Meta
    public ?string $remarks = null;
    public ?DateTimeImmutable $createdAt = null;
    public ?DateTimeImmutable $updatedAt = null;
    public ?int $createdBy = null;
}
