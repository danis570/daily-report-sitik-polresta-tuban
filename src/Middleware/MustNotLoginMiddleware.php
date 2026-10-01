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
    function before(): bool
    {
        if (isset($_COOKIE[SessionService::$cookieName])) {
            View::redirect('/');
            return false;
        }

        return true;
    }
}
