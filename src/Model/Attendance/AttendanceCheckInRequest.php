<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance;

class AttendanceCheckInRequest
{
 public int $userId;
    public ?string $qrCode = null;
    public ?string $statusCode = null;   // 'H' atau 'D'
    public ?string $remarks = null;
}
