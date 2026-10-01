<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Domain;

use DateTimeImmutable;

class User
{
    public ?int $id = null;
    public string $email;
    public string $password;
    public UserRole $role;
    public DateTimeImmutable $createdAt;
    public ?DateTimeImmutable $updatedAt = null;
    public ?DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
    