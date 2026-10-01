<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

class HomeController extends BaseController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();
        
        $userRepository = new UserRepository($pdo);
        $profileRepository = new ProfileRepository($pdo);
        $sessionRepository = new SessionRepository($pdo);

        parent::__construct($userRepository, $profileRepository, $sessionRepository);

        $this->sessionService = new SessionService($sessionRepository);
    }

    public function index(): void
    {
        $sId = $_COOKIE[SessionService::$cookieName] ?? null;
        $user = null;

        if ($sId) {
            $currentSession = $this->sessionRepository->findById($sId);
            if ($currentSession && isset($currentSession->userId)) {
                $user = $this->userRepository->findById((int) $currentSession->userId);
            }
        }

        if ($user !== null) {
            if ($user->role->value === 'admin') {
                $allUserCount = $this->userRepository->countAllUser();
                $allUsersData = $this->userRepository->findAll();

                View::render('Admin', 'Admin/dashboard', [
                    'title' => 'Laporan Harian SITIK Polresta Tuban',
                    'user' => $user,        
                    'user_sum' => $allUserCount, 
                    'users' => $allUsersData 
                ]);
            }

            View::render('User', 'User/dashboard', [
                'title' => 'Laporan Harian SITIK Polresta Tuban',
                'user' => $user
            ]);
            return;
        }

        View::render('Public', 'Public/home', [
            'title' => 'Laporan Harian SITIK Polresta Tuban'
        ]);
    }

    public function about(): void
    {
        View::render('Public', 'Public/about', [
            'title' => 'tentang Sistem'
        ]);
    }
}