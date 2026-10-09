<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Attendance;

use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Attendance;

class AttendanceResponse
{
    public Attendance $attendance;
    public string $action = '';   // 'check_in', 'check_out', 'manual'
    public string $message = '';
}
