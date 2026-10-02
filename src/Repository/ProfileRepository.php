<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PDO;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Profile;

class ProfileRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Profile $profile): Profile
    {
        $stmt = $this->pdo->prepare("INSERT INTO profiles (name, avatar, user_id) VALUES (?, ?, ?)");
        $stmt->execute([
            $profile->name,
            $profile->avatar,
            $profile->userId
        ]);

        $profile->id = (int) $this->pdo->lastInsertId();
        return $profile;
    }

    public function findById(int $id): ?Profile
    {
        $stmt = $this->pdo->prepare("SELECT * FROM profiles WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $this->mapRowToProfile($result) : null;
    }

    public function findByUserId(?int $userId): ?Profile
    {
        if ($userId === null) {
            return null;
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $this->mapRowToProfile($result) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM profiles");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            return [];
        }

        $profiles = [];
        foreach ($results as $row) {
            $profiles[] = $this->mapRowToProfile($row);
        }

        return $profiles;
    }

    public function update(Profile $profile): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE profiles
            SET name = ?, avatar = ?, user_id = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $profile->name,
            $profile->avatar,
            $profile->userId,
            $profile->id
        ]);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM profiles WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    public function deleteByUserId(int $userId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM profiles WHERE user_id = ?");
        $stmt->execute([$userId]);

        return $stmt->rowCount() > 0;
    }

    public function deleteAll(): void
    {
        $this->pdo->exec("DELETE FROM profiles");
    }

    private function mapRowToProfile(array $result): Profile
    {
        $profile = new Profile();
        $profile->id = (int) $result['id'];
        $profile->name = $result['name'];
        $profile->avatar = $result['avatar'];
        $profile->userId = (int) $result['user_id'];

        return $profile;
    }
}
