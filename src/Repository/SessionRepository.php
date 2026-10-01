<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Session;

class SessionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Session $session): Session
    {
        $stmt = $this->pdo->prepare("INSERT INTO sessions(
        id, user_id)
        VALUES (?, ?)");
        $stmt->execute([
            $session->id,
            $session->userId
        ]);

        return $session;
    }

    public function findByUserId(int $userId): ?Session
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sessions WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $response = new Session();
        $response->id = $result['id'];
        $response->userId = $result['user_id'];

        return $response;
    }

    public function findById(string $id): ?Session
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $response = new Session();
        $response->id = $result['id'];
        $response->userId = $result['user_id'];

        return $response;
    }

    public function deleteByUserId(int $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sessions where user_id = ?");
        $stmt->execute([$userId]);
    }

    public function deleteAll()
    {
        $this->pdo->exec("DELETE FROM sessions");
    }
}
