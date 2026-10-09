<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\User;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\UserRole;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Profile\ProfileUpdateRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class ProfileServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private ProfileRepository $profileRepository;
    private ProfileService $profileService;
    private User $dummyUser;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->profileRepository = new ProfileRepository($connection);
        $this->profileService = new ProfileService($this->profileRepository, $this->userRepository);

        // Bersihkan data lama di database testing untuk mencegah konflik id
        $this->profileRepository->deleteAll();
        $this->userRepository->deleteAll();

        // Siapkan 1 data user sebagai foreign key yang sah
        $user = new User();
        $user->email = "service.tester@sitikpolrestatuban.id";
        $user->password = "rahasia123";
        $user->role = UserRole::USER;
        $this->dummyUser = $this->userRepository->save($user);
    }

    protected function tearDown(): void
    {
        $this->profileRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testUpdateProfileSuccessSaveNew(): void
    {
        // Skenario: Profil belum ada di DB, harusnya otomatis memanggil ->save()
        $request = new ProfileUpdateRequest();
        $request->userId = $this->dummyUser->id;
        $request->name = "Aiptu Bambang";
        $request->avatar = "bambang.jpg";

        $response = $this->profileService->update($request);

        $this->assertNotNull($response->profile->id);
        $this->assertEquals("Aiptu Bambang", $response->profile->name);
        $this->assertEquals("bambang.jpg", $response->profile->avatar);
        $this->assertEquals($this->dummyUser->id, $response->profile->userId);
    }

    public function testUpdateProfileSuccessUpdateExisting(): void
    {
        // Skenario: Buat profil awal terlebih dahulu
        $request1 = new ProfileUpdateRequest();
        $request1->userId = $this->dummyUser->id;
        $request1->name = "Nama Lama";
        $request1->avatar = "avatar_lama.png";
        $this->profileService->update($request1);

        // Eksekusi pembaruan data (Update)
        $request2 = new ProfileUpdateRequest();
        $request2->userId = $this->dummyUser->id;
        $request2->name = "Nama Baru (Updated)";
        $request2->avatar = "avatar_baru.png";

        $response = $this->profileService->update($request2);

        $this->assertEquals("Nama Baru (Updated)", $response->profile->name);
        $this->assertEquals("avatar_baru.png", $response->profile->avatar);

        // Pastikan di database datanya benar-benar ter-update
        $dbProfile = $this->profileRepository->findByUserId($this->dummyUser->id);
        $this->assertEquals("Nama Baru (Updated)", $dbProfile->name);
    }

    public function testUpdateProfileValidationErrorNameEmpty(): void
    {
        // Skenario: Mengosongkan kolom nama wajib memicu exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Nama tidak boleh kosong!");

        $request = new ProfileUpdateRequest();
        $request->userId = $this->dummyUser->id;
        $request->name = ""; // Kosong
        $request->avatar = "test.png";

        $this->profileService->update($request);
    }

    public function testUpdateProfileUserNotFound(): void
    {
        // Skenario: Menggunakan userId palsu (999) yang tidak terdaftar di DB
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User tidak ditemukan!");

        $request = new ProfileUpdateRequest();
        $request->userId = 999;
        $request->name = "Profil Tanpa User";
        $request->avatar = "test.png";

        $this->profileService->update($request);
    }

    public function testUpdateAutoGeneratesQrCode(): void
{
    $request = new ProfileUpdateRequest();
    $request->userId = $this->dummyUser->id;
    $request->name = 'Test User';

    $response = $this->profileService->update($request);

    self::assertNotNull($response->profile->qrCode);
    self::assertStringStartsWith('TIK-', $response->profile->qrCode);
}

public function testUpdateSavesNrpRankPosition(): void
{
    $request = new ProfileUpdateRequest();
    $request->userId = $this->dummyUser->id;
    $request->name = 'Test';
    $request->nrp = '82031270';
    $request->rank = 'AIPDA';
    $request->position = 'PS. KASI TIK';

    $response = $this->profileService->update($request);

    self::assertSame('82031270', $response->profile->nrp);
    self::assertSame('AIPDA', $response->profile->rank);
    self::assertSame('PS. KASI TIK', $response->profile->position);
}
}
