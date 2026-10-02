<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserLoginRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserLoginResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);
        $this->userRepository->deleteAll();
    }

    function testRegister()
    {
        $request = new UserRegisterRequest();
        $request->email = 'ahmad@gmail.com';
        $request->password = 'password123';               // ✅ diperbaiki

        $result = $this->userService->register($request);

        self::assertEquals($request->email, $result->user->email);
        self::assertTrue(password_verify($request->password, $result->user->password));
    }

    function testRegisterEmailEmpty()
    {
        $request = new UserRegisterRequest();
        $request->email = '';
        $request->password = 'password123';               // ✅ diperbaiki

        self::expectException(Exception::class);
        self::expectExceptionMessage('email is required');

        $this->userService->register($request);
    }

    function testRegisterEmailPassword()
    {
        $request = new UserRegisterRequest();
        $request->email = 'ok@gmail.com';
        $request->password = '';

        self::expectException(Exception::class);
        self::expectExceptionMessage('password is required');

        $this->userService->register($request);
    }

    function testLoginSuccess()
    {
        $request = new UserRegisterRequest();
        $request->email = 'ahmad@gmail.com';
        $request->password = 'password123';               // ✅ diperbaiki
        $this->userService->register($request);

        $requestLogin = new UserLoginRequest();
        $requestLogin->email = 'ahmad@gmail.com';
        $requestLogin->password = 'password123';          // ✅ diperbaiki
        $result = $this->userService->login($requestLogin);

        self::assertInstanceOf(UserLoginResponse::class, $result);
    }

    function testLoginFailedPassword()
    {
        $request = new UserRegisterRequest();
        $request->email = 'ahmad@gmail.com';
        $request->password = 'password123';               // ✅ diperbaiki
        $this->userService->register($request);

        self::expectException(Exception::class);
        self::expectExceptionMessage('Email or password is wrong');

        $requestLogin = new UserLoginRequest();
        $requestLogin->email = 'ahmad@gmail.com';
        $requestLogin->password = 'password456';          // ✅ beda dari yang benar
        $result = $this->userService->login($requestLogin);

        self::assertInstanceOf(UserLoginResponse::class, $result);
    }

    function testLoginFailedEmail()
    {
        $request = new UserRegisterRequest();
        $request->email = 'ah@gmail.com';
        $request->password = 'password123';               // ✅ diperbaiki
        $this->userService->register($request);

        self::expectException(Exception::class);
        self::expectExceptionMessage('Email or password is wrong');

        $requestLogin = new UserLoginRequest();
        $requestLogin->email = 'ahmad@gmail.com';
        $requestLogin->password = 'password123';          // ✅ tetap
        $result = $this->userService->login($requestLogin);

        self::assertInstanceOf(UserLoginResponse::class, $result);
    }
}