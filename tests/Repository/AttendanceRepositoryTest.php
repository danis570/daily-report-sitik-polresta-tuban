<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Attendance;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;

class AttendanceRepositoryTest extends TestCase
{
    private AttendanceRepository $repo;
    private UserRepository $userRepository;
    private ProfileRepository $profileRepository;
    private User $user1;
    private User $user2;

    protected function setUp(): void
    {
        Database::clearConnection();
        $pdo = Database::getConnection();

        $this->repo             = new AttendanceRepository($pdo);
        $this->userRepository   = new UserRepository($pdo);
        $this->profileRepository = new ProfileRepository($pdo);

        // Cleanup — urutan: anak dulu
        $this->repo->deleteAll();
        $this->profileRepository->deleteAll();
        $this->userRepository->deleteAll();

        // Seed user
        $this->user1 = $this->createUser('att1@test.com');
        $this->user2 = $this->createUser('att2@test.com');
    }

    private function createUser(string $email): User
    {
        $user = new User();
        $user->email = $email;
        $user->password = 'password123';
        $user->role = UserRole::USER;
        return $this->userRepository->save($user);
    }

    public function testSaveSuccess(): void
    {
        $attendance = new Attendance();
        $attendance->userId = $this->user1->id;
        $attendance->attendanceDate = new DateTimeImmutable('2026-10-08');
        $attendance->checkInTime = new DateTimeImmutable('2026-10-08 08:00:00');
        $attendance->checkInQr = 'ABSEN-SITIK-TUBAN';
        $attendance->statusCode = 'H';

        $saved = $this->repo->save($attendance);

        self::assertNotNull($saved->id);
        self::assertSame($this->user1->id, $saved->userId);
        self::assertSame('H', $saved->statusCode);
    }

    public function testFindByUserAndDate(): void
    {
        $attendance = new Attendance();
        $attendance->userId = $this->user1->id;
        $attendance->attendanceDate = new DateTimeImmutable('2026-10-08');
        $attendance->statusCode = 'H';
        $this->repo->save($attendance);

        $found = $this->repo->findByUserAndDate(
            $this->user1->id,
            new DateTimeImmutable('2026-10-08')
        );

        self::assertNotNull($found);
        self::assertSame('H', $found->statusCode);
    }

    public function testFindByUserAndDateNotFound(): void
    {
        $found = $this->repo->findByUserAndDate(
            $this->user1->id,
            new DateTimeImmutable('2026-10-08')
        );
        self::assertNull($found);
    }

    public function testFindUserIdsByDate(): void
    {
        foreach ([$this->user1, $this->user2] as $u) {
            $attendance = new Attendance();
            $attendance->userId = $u->id;
            $attendance->attendanceDate = new DateTimeImmutable('2026-10-08');
            $attendance->statusCode = 'H';
            $this->repo->save($attendance);
        }

        $ids = $this->repo->findUserIdsByDate(new DateTimeImmutable('2026-10-08'));

        self::assertCount(2, $ids);
        self::assertContains($this->user1->id, $ids);
        self::assertContains($this->user2->id, $ids);
    }

    public function testUpdateSuccess(): void
    {
        $attendance = new Attendance();
        $attendance->userId = $this->user1->id;
        $attendance->attendanceDate = new DateTimeImmutable('2026-10-08');
        $attendance->checkInTime = new DateTimeImmutable('2026-10-08 08:00:00');
        $attendance->statusCode = 'H';
        $saved = $this->repo->save($attendance);

        // Check-out
        $saved->checkOutTime = new DateTimeImmutable('2026-10-08 16:00:00');
        $saved->checkOutQr = 'ABSEN-SITIK-TUBAN';
        $this->repo->update($saved);

        $reloaded = $this->repo->findById($saved->id);

        self::assertNotNull($reloaded->checkOutTime);
        self::assertSame('2026-10-08 16:00:00', $reloaded->checkOutTime->format('Y-m-d H:i:s'));
    }

    public function testCountByStatusInRange(): void
    {
        // User1: 2× H, 1× D
        foreach (['2026-10-01', '2026-10-02'] as $date) {
            $a = new Attendance();
            $a->userId = $this->user1->id;
            $a->attendanceDate = new DateTimeImmutable($date);
            $a->statusCode = 'H';
            $this->repo->save($a);
        }
        $a = new Attendance();
        $a->userId = $this->user1->id;
        $a->attendanceDate = new DateTimeImmutable('2026-10-03');
        $a->statusCode = 'D';
        $this->repo->save($a);

        // User2: 1× H
        $a = new Attendance();
        $a->userId = $this->user2->id;
        $a->attendanceDate = new DateTimeImmutable('2026-10-01');
        $a->statusCode = 'H';
        $this->repo->save($a);

        $result = $this->repo->countByStatusInRange(
            new DateTimeImmutable('2026-10-01'),
            new DateTimeImmutable('2026-10-31')
        );

        self::assertSame(2, $result[$this->user1->id]['H']);
        self::assertSame(1, $result[$this->user1->id]['D']);
        self::assertSame(1, $result[$this->user2->id]['H']);
    }

    public function testFindByDate(): void
    {
        foreach ([$this->user1, $this->user2] as $u) {
            $a = new Attendance();
            $a->userId = $u->id;
            $a->attendanceDate = new DateTimeImmutable('2026-10-08');
            $a->statusCode = 'H';
            $this->repo->save($a);
        }

        $result = $this->repo->findByDate(new DateTimeImmutable('2026-10-08'));

        self::assertCount(2, $result);
    }
}