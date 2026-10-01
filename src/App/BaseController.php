<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\App;

use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

abstract class BaseController
{
    protected UserRepository $userRepository;
    protected ProfileRepository $profileRepository;
    protected SessionRepository $sessionRepository;

    public function __construct(
        UserRepository $userRepository,
        ProfileRepository $profileRepository,
        SessionRepository $sessionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
        $this->sessionRepository = $sessionRepository;

        $sessionId = $_COOKIE[SessionService::$cookieName] ?? null;

        if ($sessionId) {
            $currentSession = $this->sessionRepository->findById($sessionId);

            if ($currentSession) {
                $currentUser = $this->userRepository->findById($currentSession->userId);
                $currentProfile = $this->profileRepository->findByUserId($currentSession->userId);

                View::share('currentUser', $currentUser);
                View::share('currentProfile', $currentProfile);
            }
        }
    }
}
