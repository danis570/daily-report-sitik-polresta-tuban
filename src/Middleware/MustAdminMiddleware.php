<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Middleware;

use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

class MustAdminMiddleware implements Middleware
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
        $sId = $_COOKIE[SessionService::$cookieName];
        $currentUser = $this->sessionRepository->findById($sId);

        $user = $this->userRepository->findById((int) $currentUser->userId);

        if (!$user || $user->role->value !== 'admin') {
            View::redirect('/');
            return false;
        }

        return true;
    }

}
