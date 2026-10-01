<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserLoginRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\User\UserRegisterRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\UserService;

class UserController extends BaseController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        // 1. Inisialisasi repositori untuk dikirim ke parent class
        $userRepository = new UserRepository($pdo);
        $profileRepository = new ProfileRepository($pdo);
        $sessionRepository = new SessionRepository($pdo);

        // 2. Kirim dependensi ke constructor BaseController
        parent::__construct($userRepository, $profileRepository, $sessionRepository);

        // 3. Inisialisasi service lokal controller ini
        $this->userService = new UserService($userRepository);
        $this->sessionService = new SessionService($this->sessionRepository);
    }

    public function register(): void
    {
        View::render('Admin', 'Admin/register', ['title' => 'Register']);
    }

    public function postRegister(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new UserRegisterRequest();
            $request->email = $_POST['email'];
            $request->password = $_POST['password'];

            try {
                $this->userService->register($request);
                View::flashMessage('Sukses menambah pengguna baru');
                View::redirect('/register');
            } catch (Exception $e) {
                View::render(
                    'Admin',
                    'Admin/register',
                    [
                        'title' => 'Register',
                        'error' => $e->getMessage()
                    ]
                );
            }
        }
    }

    public function login(): void
    {
        View::render('Public', 'Public/login', ['title' => 'Login']);
    }

    public function postLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new UserLoginRequest();
            $request->email = $_POST['email'];
            $request->password = $_POST['password'];

            try {
                $this->userService->login($request);
                View::redirect('/');
            } catch (Exception $e) {
                View::render('Public', 'Public/login', [
                    'title' => 'Login',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function users()
    {
        $allUserCount = $this->userRepository->countAllUser();
        $allUsersData = $this->userRepository->findAll();

        View::render('Admin', 'Admin/users', [
            'title' => 'Data Users',
            'user_sum' => $allUserCount,
            'users' => $allUsersData
        ]);
    }

    public function postDelete(): void
    {
        try {
            $this->userService->deleteById($_POST['id']);
            $this->sessionRepository->deleteByUserId($_POST['id']);
            $this->profileRepository->deleteByUserId($_POST['id']);
            View::flashMessage('Sukses menghapus user');
            View::redirect('/users');
        } catch (Exception $e) {
            View::flashMessage('Gagal menghapus user');
            View::redirect('/users');
        }
    }

    public function logout(): void
    {
        // Gunakan null coalescing agar aman jika kuki sudah terhapus duluan
        $sId = $_COOKIE[SessionService::$cookieName] ?? null;

        if ($sId) {
            $currentUser = $this->sessionRepository->findById($sId);
            if ($currentUser) {
                $this->sessionRepository->deleteByUserId($currentUser->userId);
            }
        }

        $this->sessionService->delete();
        View::redirect('/');
    }
}
