<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PDO;
use PDOException;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): User
    {
        $stmt = $this->pdo->prepare("INSERT INTO users(
        email, password, role)
        VALUES (?, ?, ?)");
        $stmt->execute([
            $user->email,
            $user->password,
            $user->role->value,
        ]);

        $user->id = $this->pdo->lastInsertId();
        return $user;
    }

    public function findById(?int $id): ?User
    {
        if ($id === null) {
            return null;
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $user = new User();
        $user->id = $result['id'];
        $user->email = $result['email'];
        $user->password = $result['password'];
        $user->role = UserRole::from($result['role']);
        $user->createdAt = new DateTimeImmutable($result['created_at']);
        $user->updatedAt = $result['updated_at'] ? new DateTimeImmutable($result['updated_at']) : null;
        $user->deletedAt = $result['deleted_at'] ? new DateTimeImmutable($result['deleted_at']) : null;

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $user = new User();
        $user->id = $result['id'];
        $user->email = $result['email'];
        $user->password = $result['password'];
        $user->role = UserRole::from($result['role']);
        $user->createdAt = new DateTimeImmutable($result['created_at']);
        $user->updatedAt = $result['updated_at'] ? new DateTimeImmutable($result['updated_at']) : null;
        $user->deletedAt = $result['deleted_at'] ? new DateTimeImmutable($result['deleted_at']) : null;

        return $user;
    }

    public function countAllUser(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return (int) $stmt->fetchColumn();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function deleteById(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function deleteAll()
    {
        $this->pdo->exec("DELETE FROM users");
    }

}