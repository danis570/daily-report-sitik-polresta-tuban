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

    function testLogin()
    {
        $this->userController->login();

        self::expectOutputRegex('[Login]');
    }
}
