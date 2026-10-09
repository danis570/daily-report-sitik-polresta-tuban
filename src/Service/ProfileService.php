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
        // 1. Validasi nama
        if ($request->name === null || trim($request->name) === "") {
            throw new Exception("Nama tidak boleh kosong!");
        }

        // 2. Pastikan user ada
        $user = $this->userRepository->findById($request->userId);
        if ($user === null) {
            throw new Exception("User tidak ditemukan!");
        }

        // 3. Ambil profile
        $profile = $this->profileRepository->findByUserId($request->userId);

        if ($profile === null) {
            // === CREATE PROFILE BARU ===
            $profile = new Profile();
            $profile->userId = $request->userId;
            $profile->name = htmlspecialchars(trim($request->name));
            $profile->avatar = $request->avatar ?? 'default-avatar.png';

            // Field baru
            $profile->nrp = $this->sanitize($request->nrp);
            $profile->rank = $this->sanitize($request->rank);
            $profile->position = $this->sanitize($request->position);

            // Auto-generate qr_code kalau belum ada
            $profile->qrCode = $this->generateQrCode($request->userId);

            $this->profileRepository->save($profile);

        } else {
            // === UPDATE PROFILE EXISTING ===
            $profile->name = htmlspecialchars(trim($request->name));

            if ($request->avatar !== null && $request->avatar !== "") {
                $profile->avatar = $request->avatar;
            }

            // Field baru — hanya update kalau dikirim (bukan null)
            if ($request->nrp !== null) {
                $profile->nrp = $this->sanitize($request->nrp);
            }
            if ($request->rank !== null) {
                $profile->rank = $this->sanitize($request->rank);
            }
            if ($request->position !== null) {
                $profile->position = $this->sanitize($request->position);
            }

            // Pastikan qr_code ada (untuk profile lama yang belum punya)
            if (empty($profile->qrCode)) {
                $profile->qrCode = $this->generateQrCode($request->userId);
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

    /**
     * Generate qr_code unik berdasarkan user_id.
     * Format: TIK-XXXX (4 digit, padded).
     */
    private function generateQrCode(int $userId): string
    {
        $base = 'TIK-' . str_pad((string) $userId, 4, '0', STR_PAD_LEFT);

        // Kalau sudah ada yang pakai, tambahkan suffix
        $qrCode = $base;
        $suffix = 1;

        while ($this->profileRepository->existsByQrCode($qrCode)) {
            $qrCode = $base . '-' . $suffix;
            $suffix++;
        }

        return $qrCode;
    }

    /**
     * Sanitize optional string — trim, atau null kalau kosong.
     */
    private function sanitize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim($value);
        return $value === '' ? null : htmlspecialchars($value);
    }
}