<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;

    private SessionRepository $sessionRepository;
    private UserService $userService;
    private UserRepository $userRepository;
    protected function setUp(): void
    {
        $pdo = Database::getConnection();
        $this->sessionRepository = new SessionRepository($pdo);
        $this->sessionService = new SessionService($this->sessionRepository);
        $this->userRepository = new UserRepository($pdo);
        $this->userService = new UserService($this->userRepository);

        $this->userRepository->deleteAll();
        $this->sessionRepository->deleteAll();
    }

    function testCreate()
    {
        $user = new User();
        $user->email = 'danish';
        $user->password = password_hash('123', PASSWORD_BCRYPT);
        $user->role = UserRole::ADMIN;

        $userResult = $this->userRepository->save($user);

        $session = $this->sessionService->create($userResult->id);

        self::assertEquals($session->userId, $userResult->id);
    }


}
