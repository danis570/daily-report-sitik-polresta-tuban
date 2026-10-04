<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Middleware;

use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

class MustNotLoginMiddleware implements Middleware
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->sessionRepository = new SessionRepository($pdo);
        $this->sessionService = new SessionService($this->sessionRepository);
        $this->userRepository = new UserRepository($pdo);
    }

    public function before(): bool
    {
        $sId = $_COOKIE[SessionService::$cookieName] ?? null;

        // 1. Tidak ada cookie → user belum login → boleh akses /login
        if ($sId === null || $sId === '') {
            return true;
        }

        // 2. Cookie ada → cek session di DB
        $session = $this->sessionRepository->findById($sId);

        // 3. Session tidak ada di DB → cookie sudah invalid → hapus & boleh login
        if ($session === null) {
            $this->clearCookie();
            return true;
        }

        // 4. Cek user masih ada
        $user = $this->userRepository->findById((int) $session->userId);

        if ($user === null) {
            $this->clearCookie();
            return true;
        }

        // 5. Session + user valid → sudah login → redirect ke dashboard
        View::redirect('/');
        return false;
    }

    /**
     * Hapus cookie session di browser.
     */
    private function clearCookie(): void
    {
        setcookie(SessionService::$cookieName, '', time() - 3600, '/');
        unset($_COOKIE[SessionService::$cookieName]);
    }
}