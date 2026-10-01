<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportOptionRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ReportOptionService;

class ReportOptionController extends BaseController
{
    private ReportOptionRepository $reportOptionRepository;
    private ReportOptionService $reportOptionService;

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
        $this->reportOptionService = new ReportOptionService($this->reportOptionRepository);
    }

    public function reportOptions()
    {
        // 1. Ambil semua data pilihan laporan dari database
        $allOptions = $this->reportOptionRepository->findAll();

        // 2. Render halaman dan kirimkan datanya ke View
        if (!empty($allOptions)) {
            View::render('User', 'User/Report/report-options', [
                'title' => 'Kelola Pilihan Laporan',
                'options' => $allOptions
            ]);
            return;
        }

        // Jika data kosong
        View::render('User', 'User/Report/report-options', [
            'title' => 'Kelola Pilihan Laporan',
            'error' => 'Belum ada pilihan laporan yang terdaftar',
            'options' => []
        ]);
    }

    public function addOption()
    {
        // Menampilkan halaman form input untuk Report Option
        View::render('User', 'User/Report/report-option-add', [
            'title' => 'Tambah Pilihan Laporan Baru'
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
                'error' => $exception->getMessage(),
                // Kirim balik data input lama agar user tidak perlu mengetik ulang jika ada error
                'oldInput' => $_POST
            ]);
        }
    }

    public function editOption(int $id)
    {
        $option = $this->reportOptionRepository->findById($id);

        if ($option === null) {
            View::render('User', 'User/Report/report-options', [
                'title' => 'Kelola Pilihan Laporan',
                'error' => 'Pilihan laporan tidak ditemukan.',
                'options' => $this->reportOptionRepository->findAll()
            ]);
            return;
        }

        // 3. Render halaman formulir edit dan kirim data option-nya
        View::render('User', 'User/Report/report-option-edit', [
            'title' => 'Ubah Pilihan Laporan',
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
                'error' => $exception->getMessage(),
                'options' => $this->reportOptionRepository->findAll()
            ]);
        }
    }


}
