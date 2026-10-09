<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Model\Profile;

class ProfileUpdateRequest
{
    public int $userId;
    public ?string $name = null;
    public ?string $avatar = null;
     public ?string $nrp = null;
    public ?string $rank = null;
    public ?string $position = null;
}
