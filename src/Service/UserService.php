<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserLoginRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserLoginResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;
    private SessionService $sessionService;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository);
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->registerValidation($request);

        $user = new User();
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_BCRYPT);
        $user->role = UserRole::USER;

        $result = $this->userRepository->save($user);

        $response = new UserRegisterResponse();
        $response->user = $result;
        return $response;
    }

    private function registerValidation(UserRegisterRequest $request): void
    {
        if (trim($request->email) == '') {
            throw new Exception('email is required');
        }

        if (trim($request->password) == '') {
            throw new Exception('password is required');
        }

        // Validasi format email harus berakhiran @gmail.com
        if (!str_ends_with(strtolower(trim($request->email)), '@gmail.com')) {
            throw new Exception('email tidak valid');
        }

        // Validasi panjang password minimal 8 karakter
        if (strlen($request->password) < 8) {
            throw new Exception('minimal password 8 karakter');
        }

        // Validasi email sudah terdaftar
        $existingUser = $this->userRepository->findByEmail($request->email);
        if ($existingUser !== null) {
            throw new Exception('email sudah terdaftar');
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->loginValidation($request);
        $user = $this->userRepository->findByEmail($request->email);

        if ($user === null) {
            throw new Exception('Email or password is wrong');
        }

        $auth = password_verify($request->password, $user->password);
        if ($auth == false) {
            throw new Exception('Email or password is wrong');
        }

        $this->sessionService->create($user->id);

        $response = new UserLoginResponse();
        $response->user = $user;
        return $response;
    }

    private function loginValidation(UserLoginRequest $request): void
    {
        if (trim($request->email) == '') {
            throw new Exception('email is required');
        }
        if (trim($request->password) == '') {
            throw new Exception('password is required');
        }
    }

    public function deleteById(int $userId)
    {
        $this->userRepository->deleteById($userId);
    }
}