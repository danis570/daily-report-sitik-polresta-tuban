<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Profile;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;

class ProfileRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private ProfileRepository $profileRepository;
    private User $dummyUser;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->profileRepository = new ProfileRepository($connection);

        // Bersihkan data lama untuk menghindari konflik data testing
        $this->profileRepository->deleteAll();
        $this->userRepository->deleteAll();

        // Buat 1 user utama yang selalu siap digunakan untuk kebutuhan foreign key user_id
        $user = new User();
        $user->email = "profile.tester@sitikpolrestatuban.id";
        $user->password = "password123";
        $user->role = UserRole::USER;
        $this->dummyUser = $this->userRepository->save($user);
    }

    protected function tearDown(): void
    {
        // Bersihkan seluruh data tabel setelah pengujian selesai
        $this->profileRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess(): void
    {
        $profile = new Profile();
        $profile->name = "Aiptu Budi Hartono";
        $profile->avatar = "budi_avatar.png";
        $profile->userId = $this->dummyUser->id;

        $result = $this->profileRepository->save($profile);

        $this->assertNotNull($result->id);
        $this->assertEquals($profile->name, $result->name);
        $this->assertEquals($profile->avatar, $result->avatar);
        $this->assertEquals($profile->userId, $result->userId);
    }

    public function testFindByIdSuccess(): void
    {
        $profile = new Profile();
        $profile->name = "Brigadir Ahmad";
        $profile->avatar = "ahmad_profile.jpg";
        $profile->userId = $this->dummyUser->id;

        $savedProfile = $this->profileRepository->save($profile);
        $foundProfile = $this->profileRepository->findById($savedProfile->id);

        $this->assertNotNull($foundProfile);
        $this->assertEquals($savedProfile->id, $foundProfile->id);
        $this->assertEquals($savedProfile->name, $foundProfile->name);
    }

    public function testFindByIdNotFound(): void
    {
        $foundProfile = $this->profileRepository->findById(999);
        $this->assertNull($foundProfile);
    }

    public function testFindByUserIdSuccess(): void
    {
        $profile = new Profile();
        $profile->name = "Briptu Lestari";
        $profile->avatar = "lestari.png";
        $profile->userId = $this->dummyUser->id;

        $this->profileRepository->save($profile);
        $foundProfile = $this->profileRepository->findByUserId($this->dummyUser->id);

        $this->assertNotNull($foundProfile);
        $this->assertEquals($profile->name, $foundProfile->name);
        $this->assertEquals($this->dummyUser->id, $foundProfile->userId);
    }

    public function testFindByUserIdNotFound(): void
    {
        $foundProfile = $this->profileRepository->findByUserId(999);
        $this->assertNull($foundProfile);
    }

    public function testFindAll(): void
    {
        $profile = new Profile();
        $profile->name = "Anggota Polres 1";
        $profile->avatar = "default.png";
        $profile->userId = $this->dummyUser->id;
        $this->profileRepository->save($profile);

        $result = $this->profileRepository->findAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        // Menyesuaikan jika ProfileRepository->findAll() mengembalikan objek domain Profile
        $this->assertEquals("Anggota Polres 1", $result[0]->name);
    }

    public function testUpdateSuccess(): void
    {
        $profile = new Profile();
        $profile->name = "Nama Lama";
        $profile->avatar = "lama.png";
        $profile->userId = $this->dummyUser->id;
        $savedProfile = $this->profileRepository->save($profile);

        // Ubah data objek domain
        $savedProfile->name = "Nama Baru (Updated)";
        $savedProfile->avatar = "baru.png";

        $isUpdated = $this->profileRepository->update($savedProfile);
        $this->assertTrue($isUpdated);

        // Verifikasi perubahan ke database
        $updatedProfile = $this->profileRepository->findById($savedProfile->id);
        $this->assertEquals("Nama Baru (Updated)", $updatedProfile->name);
        $this->assertEquals("baru.png", $updatedProfile->avatar);
    }

    public function testDeleteByIdSuccess(): void
    {
        $profile = new Profile();
        $profile->name = "Hapus Akun";
        $profile->avatar = "hapus.png";
        $profile->userId = $this->dummyUser->id;
        $savedProfile = $this->profileRepository->save($profile);

        $isDeleted = $this->profileRepository->deleteById($savedProfile->id);

        $this->assertTrue($isDeleted);
        $this->assertNull($this->profileRepository->findById($savedProfile->id));
    }

    public function testDeleteByUserIdSuccess(): void
    {
        $profile = new Profile();
        $profile->name = "Hapus Lewat User Id";
        $profile->avatar = "avatar.png";
        $profile->userId = $this->dummyUser->id;
        $this->profileRepository->save($profile);

        $isDeleted = $this->profileRepository->deleteByUserId($this->dummyUser->id);

        $this->assertTrue($isDeleted);
        $this->assertNull($this->profileRepository->findByUserId($this->dummyUser->id));
    }

    public function testDeleteAll(): void
    {
        $profile = new Profile();
        $profile->name = "Semua Profil";
        $profile->avatar = "all.png";
        $profile->userId = $this->dummyUser->id;
        $this->profileRepository->save($profile);

        $this->profileRepository->deleteAll();
        $this->assertCount(0, $this->profileRepository->findAll());
    }

        public function testFindByQrCode(): void
    {
        $profile = new Profile();
        $profile->userId = $this->dummyUser->id;
        $profile->name = 'Test';
        $profile->qrCode = 'TIK-0001';
        $this->profileRepository->save($profile);

        $found = $this->profileRepository->findByQrCode('TIK-0001');

        self::assertNotNull($found);
        self::assertSame('TIK-0001', $found->qrCode);
    }

    public function testFindByQrCodeNotFound(): void
    {
        $result = $this->profileRepository->findByQrCode('TIK-9999');
        self::assertNull($result);
    }

    public function testExistsByQrCode(): void
    {
        $profile = new Profile();
        $profile->userId = $this->dummyUser->id;
        $profile->name = 'Test';
        $profile->qrCode = 'TIK-0001';
        $this->profileRepository->save($profile);

        self::assertTrue($this->profileRepository->existsByQrCode('TIK-0001'));
        self::assertFalse($this->profileRepository->existsByQrCode('TIK-9999'));
    }

    public function testSaveProfileWithNewFields(): void
    {
        $profile = new Profile();
        $profile->userId = $this->dummyUser->id;
        $profile->name = 'INDRA D.S.';
        $profile->nrp = '82031270';
        $profile->rank = 'AIPDA';
        $profile->position = 'PS. KASI TIK';
        $profile->qrCode = 'TIK-0001';

        $saved = $this->profileRepository->save($profile);

        self::assertNotNull($saved->id);
        self::assertSame('82031270', $saved->nrp);
        self::assertSame('AIPDA', $saved->rank);
        self::assertSame('PS. KASI TIK', $saved->position);
        self::assertSame('TIK-0001', $saved->qrCode);
    }
}