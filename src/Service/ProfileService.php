<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Profile;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Profile\ProfileUpdateRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Profile\ProfileUpdateResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;

class ProfileService
{
    private ProfileRepository $profileRepository;
    private UserRepository $userRepository;

    public function __construct(ProfileRepository $profileRepository, UserRepository $userRepository)
    {
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
    }

    public function update(ProfileUpdateRequest $request): ProfileUpdateResponse
    {
        // 1. Validasi input nama tidak boleh kosong
        if ($request->name === null || trim($request->name) === "") {
            throw new Exception("Nama tidak boleh kosong!");
        }

        // 2. Pastikan user valid dan ada di database
        $user = $this->userRepository->findById($request->userId);
        if ($user === null) {
            throw new Exception("User tidak ditemukan!");
        }

        // 3. Ambil data profil berdasarkan userId
        $profile = $this->profileRepository->findByUserId($request->userId);

        if ($profile === null) {
            // Jika profil belum pernah dibuat, inisialisasi baru (Save)
            $profile = new Profile();
            $profile->userId = $request->userId;
            $profile->name = htmlspecialchars(trim($request->name));
            $profile->avatar = $request->avatar ?? 'default-avatar.png';

            $this->profileRepository->save($profile);
        } else {
            // Jika profil sudah ada, perbarui data yang ada (Update)
            $profile->name = htmlspecialchars(trim($request->name));

            // Perbarui avatar hanya jika ada file baru yang diunggah
            if ($request->avatar !== null && $request->avatar !== "") {
                $profile->avatar = $request->avatar;
            }

            $success = $this->profileRepository->update($profile);
            if (!$success) {
                throw new Exception("Gagal memperbarui profil.");
            }
        }

        $response = new ProfileUpdateResponse();
        $response->profile = $profile;

        return $response;
    }
}
