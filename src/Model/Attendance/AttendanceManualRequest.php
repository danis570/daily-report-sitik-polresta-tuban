<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance;

class AttendanceManualRequest
{
  public int $userId;
    public string $statusCode;           // IZIN / CUTI / SKT / LD
    public ?string $remarks = null;
    public ?string $date = null;         // 'Y
}
