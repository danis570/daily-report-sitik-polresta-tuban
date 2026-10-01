<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Session;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
    }

    function testSave()
    {
        $user = new User();
        $user->email = 'ahmaddanish@gmail.com';
        $user->password = password_hash('123', PASSWORD_BCRYPT);
        $user->role = UserRole::USER;
        $result = $this->userRepository->save($user);

        $session = new Session();
        $session->id = bin2hex(random_bytes(32));
        $session->userId = $result->id;

        $result = $this->sessionRepository->save($session);

        self::assertInstanceOf(Session::class, $result);
    }

    function testDeleteByUserId()
    {
        $user = new User();
        $user->email = 'ok@gmail.com';
        $user->password = password_hash('123', PASSWORD_BCRYPT);
        $user->role = UserRole::USER;
        $result = $this->userRepository->save($user);

        $session = new Session();
        $session->id = bin2hex(random_bytes(32));
        $session->userId = $result->id;
        $this->sessionRepository->save($session);

        $this->sessionRepository->deleteByUserId($result->id);

        $sess = $this->sessionRepository->findByUserId($result->id);
        self::assertNull($sess);
    }

}
