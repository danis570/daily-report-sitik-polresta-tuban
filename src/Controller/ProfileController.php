<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Profile\ProfileUpdateRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ProfileService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

class ProfileController extends BaseController
{
    private ProfileService $profileService;

    public function __construct()
    {
        $pdo = Database::getConnection();
        
        // 1. Inisialisasi repositori untuk kebutuhan parent class
        $userRepository = new UserRepository($pdo);
        $profileRepository = new ProfileRepository($pdo);
        $sessionRepository = new SessionRepository($pdo);

        // 2. Kirim dependensi ke constructor BaseController
        parent::__construct($userRepository, $profileRepository, $sessionRepository);

        // 3. Inisialisasi service lokal controller ini
        $this->profileService = new ProfileService($this->profileRepository, $this->userRepository);
    }

    public function profile(): void
    {
        $sessionId = $_COOKIE[SessionService::$cookieName] ?? null;
        $currentUser = $this->sessionRepository->findById($sessionId);

        $profile = $this->profileRepository->findByUserId($currentUser->userId);

        View::render('User', 'User/profile', [
            'title' => 'Edit Profile',
            'profile' => $profile
        ]);
    }

    public function postUpdate(): void
    {
        try {
            $sessionId = $_COOKIE[SessionService::$cookieName] ?? null;

            if (!$sessionId) {
                throw new Exception("Anda harus login terlebih dahulu.");
            }

            $currentUser = $this->sessionRepository->findById($sessionId);

            $request = new ProfileUpdateRequest();
            $request->userId = (int) $currentUser->userId;
            $request->name = $_POST['name'] ?? null;
            $request->avatar = null; 

            // --- LOGIKA UPLOAD FILE FISIK ---
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['avatar']['tmp_name'];
                $fileName = $_FILES['avatar']['name'];

                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($fileExtension, $allowedExtensions)) {
                    throw new Exception("Format berkas tidak didukung! Hanya diperbolehkan: " . implode(', ', $allowedExtensions));
                }

                $newFileName = 'user_avatar_' . uniqid() . '_' . time() . '.' . $fileExtension;

                $uploadFileDir = __DIR__ . '/../../public/uploads/avatar/';

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $request->avatar = $newFileName;
                } else {
                    throw new Exception("Gagal mengunggah avatar ke server.");
                }
            }

            $response = $this->profileService->update($request);

            View::render('User', 'User/profile', [
                'title' => 'Edit Profile',
                'success' => 'Profil berhasil diperbarui!',
                'profile' => $response->profile
            ]);

        } catch (Exception $e) {
            View::render('User', 'User/profile', [
                'title' => 'Edit Profile',
                'error' => $e->getMessage()
            ]);
        }
    }
}