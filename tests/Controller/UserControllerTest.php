<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    private UserController $userController;

    protected function setUp(): void
    {
        $this->userController = new UserController();
    }

    public function testLogin()
    {
        // 1. Beritahu PHPUnit terlebih dahulu ekspektasi outputnya (Gunakan / agar jadi regex yang valid)
        $this->expectOutputRegex('/Login/');

        // 2. Baru jalankan method yang menghasilkan output HTML
        $this->userController->login();
    }
}
