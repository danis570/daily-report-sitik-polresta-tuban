<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Session;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;

class SessionService
{
    public static $cookieName = 'TIK-POLRESTA-S';
    private SessionRepository $sessionRepository;
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function create(int $userId): Session
    {
        $session = new Session();
        $session->id = bin2hex(random_bytes(32));
        $session->userId = $userId;

        $this->sessionRepository->save($session);
        $sessionId = $this->sessionRepository->findByUserId($userId);

        setcookie(self::$cookieName, $sessionId->id, time() + (86400 * 30), "/");

        return $session;
    }

    public function delete()
    {
        setcookie(self::$cookieName, '', time() - 3600, "/");
        unset($_COOKIE[self::$cookieName]);
    }

}