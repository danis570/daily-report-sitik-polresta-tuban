<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        Database::clearConnection();

        $pdo = Database::getConnection('dev');

        $this->userRepository = new UserRepository($pdo);
        $this->userRepository->deleteAll();
    }

    function testSaveSuccess()
    {
        $user = new User();
        $user->email = 'ahmad56danish@gmail.com';
        $user->password = password_hash('123', PASSWORD_BCRYPT);
        $user->role = UserRole::ADMIN;

        $result = $this->userRepository->save($user);
        self::assertEquals($user->email, $result->email);
        self::assertEquals($user->role, $result->role);
    }

    function testDeleteById()
    {
        $result = $this->userRepository->deleteById(23);
        self::assertIsBool($result);
        self::assertEquals(false, $result);
    }

    function testFindById()
    {
        $user = new User();
        $user->email = 'ahmaddanish@gmail.com';
        $user->password = password_hash('123', PASSWORD_BCRYPT);
        $user->role = UserRole::USER;
        $result = $this->userRepository->save($user);

        $this->userRepository->findById($result->id);

        self::assertEquals($user->email, $result->email);
        self::assertEquals($user->role->value, $result->role->value);
    }

     function testFindByEmail()
    {
        $user = new User();
        $user->email = 'ahmaddanish@gmail.com';
        $user->password = password_hash('123', PASSWORD_BCRYPT);
        $user->role = UserRole::USER;
        $result = $this->userRepository->save($user);

        $this->userRepository->findByEmail($result->email);

        self::assertEquals($user->email, $result->email);
        self::assertEquals($user->role->value, $result->role->value);
    }

}