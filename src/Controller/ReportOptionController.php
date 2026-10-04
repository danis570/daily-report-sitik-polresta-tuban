<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportOptionRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ReportOptionService;

class ReportOptionController extends BaseController
{
    private ReportOptionRepository $reportOptionRepository;
    private ReportOptionService $reportOptionService;
    private ReportItemRepository $reportItemRepository;

    public function __construct()
    {
        // 1. Ambil koneksi PDO tunggal aplikasi
        $connection = Database::getConnection();

        // 2. Instansiasi objek repository secara manual
        $userRepository = new UserRepository($connection);
        $profileRepository = new ProfileRepository($connection);
        $sessionRepository = new SessionRepository($connection);

        // 3. Kirim repository wajib ke parent (BaseController)
        parent::__construct($userRepository, $profileRepository, $sessionRepository);

        // 4. Instansiasi objek khusus untuk ReportController
        $this->reportOptionRepository = new ReportOptionRepository($connection);
        $this->reportItemRepository = new ReportItemRepository($connection);
        $this->reportOptionService = new ReportOptionService($this->reportOptionRepository, $this->reportItemRepository);
    }

    public function reportOptions()
    {
        $allOptions = $this->reportOptionRepository->findAll();

        $usage = $this->reportItemRepository->countAllOptionsUsage();

        if (!empty($allOptions)) {
            View::render('User', 'User/Report/report-options', [
                'title' => 'Kelola Pilihan Laporan',
                'current' => 'options',
                'options' => $allOptions,
                'usage' => $usage
            ]);

            return;
        }

        View::render('User', 'User/Report/report-options', [
            'title' => 'Kelola Pilihan Laporan',
            'current' => 'options',
            'error' => 'Belum ada pilihan laporan yang terdaftar',
            'options' => [],
            'usage' => []
        ]);
    }

    public function addOption()
    {
        // Menampilkan halaman form input untuk Report Option
        View::render('User', 'User/Report/report-option-add', [
            'title' => 'Tambah Pilihan Laporan Baru',
            'current' => 'options'
        ]);
    }

    public function postAddOption()
    {
        // 1. Inisialisasi Request DTO dan tangkap input mentah dari form POST
        $request = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportOptionRequest();
        $request->category = $_POST['category'] ?? null;
        $request->name = $_POST['name'] ?? null;
        $request->description = $_POST['description'] ?? null;

        try {
            // 2. Jalankan logika validasi dan penyimpanan di Layer Service
            $this->reportOptionService->create($request);

            View::flashMessage('Sukses Menambahkan Opsi');
            header("Location: /report/options");
            exit();

        } catch (Exception $exception) {
            // 4. Jika gagal (kategori/nama kosong atau duplikat), kembalikan form dengan pesan error
            View::render('User', 'User/Report/report-option-add', [
                'title' => 'Tambah Pilihan Laporan Baru',
                'current' => 'options',
                'error' => $exception->getMessage(),
                // Kirim balik data input lama agar user tidak perlu mengetik ulang jika ada error
                'oldInput' => $_POST
            ]);
        }
    }

    public function quickAdd(): void
    {
        // ✅ WAJIB: matikan display error supaya warning tidak merusak JSON
        ini_set('display_errors', '0');
        error_reporting(E_ALL);

        // Response JSON
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');

        try {
            // 1. Tangkap input
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');

            // 2. Validasi
            if ($name === '') {
                throw new Exception('Nama opsi wajib diisi.');
            }

            if ($category === '') {
                throw new Exception('Kategori wajib diisi.');
            }

            // 3. Cek duplikat — kalau sudah ada, return existing
            $existing = $this->reportOptionRepository
                ->findByCategoryAndName($category, $name);

            if ($existing !== null) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => $existing->id,
                        'name' => $existing->name,
                        'category' => $existing->category,
                    ],
                    'message' => 'Opsi sudah ada, dipilih otomatis.',
                ]);
                exit();
            }

            // 4. Simpan opsi baru via service
            $request = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportOptionRequest();
            $request->category = $category;
            $request->name = $name;
            $request->description = null;

            $this->reportOptionService->create($request);

            // ✅ Ambil ulang dari repository (lebih aman daripada `$response->option`)
            $saved = $this->reportOptionRepository
                ->findByCategoryAndName($category, $name);

            if ($saved === null) {
                throw new Exception('Gagal menyimpan opsi baru.');
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => $saved->id,
                    'name' => $saved->name,
                    'category' => $saved->category,
                ],
                'message' => 'Opsi baru berhasil ditambahkan.',
            ]);

        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        exit();
    }

    public function editOption(int $id)
    {
        $option = $this->reportOptionRepository->findById($id);

        if ($option === null) {
            View::render('User', 'User/Report/report-options', [
                'title' => 'Kelola Pilihan Laporan',
                'current' => 'options',
                'error' => 'Pilihan laporan tidak ditemukan.',
                'options' => $this->reportOptionRepository->findAll()
            ]);
            return;
        }

        // 3. Render halaman formulir edit dan kirim data option-nya
        View::render('User', 'User/Report/report-option-edit', [
            'title' => 'Ubah Pilihan Laporan',
            'current' => 'options',
            'option' => $option
        ]);
    }

    public function postEditOption(int $id)
    {
        $request = new UserUpdateReportOptionRequest();
        $request->id = $id;
        $request->category = $_POST['category'] ?? null;
        $request->name = $_POST['name'] ?? null;
        $request->description = $_POST['description'] ?? null;

        try {
            $this->reportOptionService->update($request);

            View::flashMessage("Pilihan laporan berhasil diperbarui!");

            header("Location: /report/options");
            exit();

        } catch (Exception $exception) {
            $option = $this->reportOptionRepository->findById($id);

            View::render('User', 'User/Report/report-option-edit', [
                'title' => 'Ubah Pilihan Laporan',
                'current' => 'options',
                'error' => $exception->getMessage(),
                'option' => $option
            ]);
        }
    }

    public function postDeleteOption(int $id)
    {
        try {
            $this->reportOptionService->delete($id);

            // Set pesan sukses hapus
            View::flashMessage("Pilihan laporan berhasil dihapus!");

            header("Location: /report/options");
            exit();

        } catch (Exception $exception) {
            View::render('User', 'User/Report/report-options', [
                'title' => 'Kelola Pilihan Laporan',
                'current' => 'options',
                'error' => $exception->getMessage(),
                'options' => $this->reportOptionRepository->findAll()
            ]);
        }
    }


}
