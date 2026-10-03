<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Controller;

use DateTimeImmutable;
use Exception;
use Throwable;
use Unirow2026\DailyReportSitikPolrestaTuban\App\BaseController;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database; // Pastikan class Database di-import
use Unirow2026\DailyReportSitikPolrestaTuban\App\View;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\ReportTrackingRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ProfileRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\SessionRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\UserRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ReportPdfService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ReportService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\ReportTrackingService;
use Unirow2026\DailyReportSitikPolrestaTuban\Service\SessionService;

class ReportController extends BaseController
{
    private ReportService $reportService;
    private ReportPdfService $reportPdfService;
    private ReportRepository $reportRepository;
    private ReportOptionRepository $reportOptionRepository;
    private ReportItemRepository $reportItemRepository;
    private ReportTrackingService $reportTrackingService;

    // Kosongkan parameter constructor agar Router tidak error saat memanggilnya
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
        $this->reportRepository = new ReportRepository($connection);
        $this->reportOptionRepository = new ReportOptionRepository($connection);
        $this->reportItemRepository = new ReportItemRepository($connection);
        $this->reportPdfService = new ReportPdfService();
        $this->reportService = new ReportService($this->reportRepository);
        $this->reportTrackingService = new ReportTrackingService($this->reportItemRepository, $this->reportOptionRepository);
    }

    public function reports()
{
    $startDate = $_GET['start_date'] ?? null;
    $endDate   = $_GET['end_date']   ?? null;
    $limit     = 10;

    // --- Validasi rentang tanggal ---
    if ($startDate !== null && $endDate !== null) {

        // 1. Cek format tanggal
        try {
            $startObj = new DateTimeImmutable($startDate);
            $endObj   = new DateTimeImmutable($endDate);
        } catch (Exception $e) {
            View::render('User', 'User/Report/reports', [
                'title'     => 'Kelola Laporan Harian',
                'current'   => 'report',
                'error'     => 'Format tanggal tidak valid.',
                'reports'   => [],
                'startDate' => $startDate,
                'endDate'   => $endDate,
                'total'     => 0,
                'limit'     => $limit,
                'hasMore'   => false,
            ]);
            return;
        }

        // 2. Cek end < start
        if ($endDate < $startDate) {
            View::render('User', 'User/Report/reports', [
                'title'     => 'Kelola Laporan Harian',
                'current'   => 'report',
                'error'     => 'Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.',
                'reports'   => [],
                'startDate' => $startDate,
                'endDate'   => $endDate,
                'total'     => 0,
                'limit'     => $limit,
                'hasMore'   => false,
            ]);
            return;
        }

        // 3. Cek rentang maksimal 31 hari
        $diffDays = $startObj->diff($endObj)->days + 1; // inclusive
        if ($diffDays > 31) {
            View::render('User', 'User/Report/reports', [
                'title'     => 'Kelola Laporan Harian',
                'current'   => 'report',
                'error'     => 'Rentang tanggal maksimal 31 hari (1 bulan). Rentang Anda: ' . $diffDays . ' hari.',
                'reports'   => [],
                'startDate' => $startDate,
                'endDate'   => $endDate,
                'total'     => 0,
                'limit'     => $limit,
                'hasMore'   => false,
            ]);
            return;
        }

        // ✅ Filter valid → tampilkan SEMUA
        $total   = $this->reportRepository->countByDateRange($startObj, $endObj);
        $reports = $this->reportRepository->findByDateRange($startObj, $endObj);
    } else {
        // Tanpa filter → 10 terbaru
        $total   = $this->reportRepository->countAll();
        $reports = $this->reportRepository->findLatest($limit);
    }

    // --- Format data (creator name + tanggal Indonesia) ---
    $reportsWithProfile = [];

    foreach ($reports as $report) {
        $creatorName = 'User telah dihapus';

        if ($report->createdBy !== null) {
            $profile = $this->profileRepository->findByUserId((int) $report->createdBy);

            if ($profile !== null && !empty($profile->name)) {
                $creatorName = $profile->name;
            } else {
                $user = $this->userRepository->findById((int) $report->createdBy);
                if ($user !== null) {
                    $creatorName = $user->email;
                }
            }
        }

        $reportsWithProfile[] = [
            'report'        => $report,
            'formattedDate' => $this->formatTanggalIndo($report->reportDate),
            'creatorName'   => $creatorName,
        ];
    }

    // --- Render ---
    View::render('User', 'User/Report/reports', [
        'title'     => 'Kelola Laporan Harian',
        'current'   => 'report',
        'reports'   => $reportsWithProfile,
        'startDate' => $startDate,
        'endDate'   => $endDate,
        'total'     => $total,
        'limit'     => $limit,
        'hasMore'   => $total > $limit,
        'error'     => null,
    ]);
}

    private function formatTanggalIndo(DateTimeImmutable $date): string
    {
        $hariArr = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $bulanArr = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $englishDay = $date->format('l');
        $dayNum = $date->format('d');
        $monthNum = $date->format('m');
        $yearNum = $date->format('Y');

        $hariIndo = $hariArr[$englishDay] ?? $englishDay;
        $bulanIndo = $bulanArr[$monthNum] ?? $monthNum;

        return "{$hariIndo}, {$dayNum} {$bulanIndo} {$yearNum}";
    }

    public function add()
    {
        View::render('User', 'User/Report/add', [
            'title' => 'Tambah Laporan Harian',
            'current' => 'add'
        ]);
    }

    public function postAdd()
    {
        $sessionId = $_COOKIE[SessionService::$cookieName] ?? null;
        $userId = null;

        if ($sessionId) {
            $currentSession = $this->sessionRepository->findById($sessionId);
            if ($currentSession) {
                $userId = $currentSession->userId;
            }
        }

        $request = new UserAddReportRequest();
        $request->reportDate = $_POST['report_date'] ?? null;
        $request->createdBy = $userId ? (int) $userId : null;

        try {
            $this->reportService->create($request);
            View::render('User', 'User/Report/add', [
                'title' => 'Tambah Laporan Harian',
                'current' => 'add',
                'success' => 'Sukses menambahkan laporan baru pergi ke menu laporan untuk menambahkan item kegiatan'
            ]);
        } catch (Exception $exception) {
            View::render('User', 'User/Report/add', [
                'title' => 'Tambah Laporan Harian',
                'current' => 'add',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function detail(string $date)
    {
        try {
            $reportDate = new \DateTimeImmutable($date);
        } catch (Exception $e) {
            header("Location: /reports");
            exit();
        }

        $report = $this->reportRepository->findByDate($reportDate);

        if ($report === null) {
            View::render('User', 'User/Report/detail', [
                'title' => 'Detail Laporan Harian',
                'current' => 'report',
                'error' => 'Laporan untuk tanggal tersebut belum dibuat.',
                'report' => null
            ]);
            return;
        }

        $profile = $this->profileRepository->findByUserId($report->createdBy);
        $creatorName = $profile && !empty($profile->name) ? $profile->name : 'User Telah Dihapus';

        $formattedDate = $this->formatTanggalIndo($report->reportDate);

        // 1. Ambil data asli domain ReportItem dari repository
        $allItems = $this->reportItemRepository->findByReportId($report->id);
        $activitiesWithNames = [];

        // 2. Gabungkan data opsi menggunakan cara array asosiatif pilihan Anda
        foreach ($allItems as $item) {
            $target = $this->reportOptionRepository->findById($item->targetOptionId);
            $activity = $this->reportOptionRepository->findById($item->activityOptionId);
            $personnel = $this->reportOptionRepository->findById($item->personnelStrengthOptionId);
            $location = $this->reportOptionRepository->findById($item->locationOptionId);
            $pic = $this->reportOptionRepository->findById($item->personInChargeOptionId);
            $result = $this->reportOptionRepository->findById($item->expectedResultOptionId);

            $activitiesWithNames[] = [
                'item' => $item, // Menyimpan objek asli Domain ReportItem
                'targetName' => $target ? $target->name : 'Tidak Diketahui',
                'activityName' => $activity ? $activity->name : 'Tidak Diketahui',
                'personnelName' => $personnel ? $personnel->name : 'Tidak Diketahui',
                'locationName' => $location ? $location->name : 'Tidak Diketahui',
                'picName' => $pic ? $pic->name : 'Tidak Diketahui',
                'expectedResultName' => $result ? $result->name : 'Tidak Diketahui',
            ];
        }

        View::render('User', 'User/Report/detail', [
            'title' => 'Detail Laporan Harian - ' . $date,
            'current' => 'report',
            'report' => $report,
            'formattedDate' => $formattedDate,
            'creatorName' => $creatorName,
            'activities' => $activitiesWithNames
        ]);
    }

    public function edit(int $id)
    {
        // 1. Cari data induk laporan asli berdasarkan ID dari database
        $report = $this->reportRepository->findById($id);

        // 2. Proteksi: Jika laporan tidak ditemukan, kembalikan ke halaman utama
        if ($report === null) {
            header("Location: /reports");
            exit();
        }

        // 3. Render halaman formulir edit induk laporan
        View::render('User', 'User/Report/edit', [
            'title' => 'Ubah Induk Laporan Harian',
            'current' => 'report',
            'report' => $report
        ]);
    }

    public function postEdit(int $id)
    {
        // Pastikan laporan induknya eksis sebelum diubah
        $report = $this->reportRepository->findById($id);
        if ($report === null) {
            header("Location: /reports");
            exit();
        }

        // Ambil ID User dari session login untuk pencatatan jejak pembaru data
        $sessionId = $_COOKIE[SessionService::$cookieName] ?? null;
        $userId = null;
        if ($sessionId) {
            $currentSession = $this->sessionRepository->findById($sessionId);
            if ($currentSession) {
                $userId = $currentSession->userId;
            }
        }

        // 1. Siapkan DTO Request Update
        $request = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportRequest();
        $request->id = $id;
        $request->reportDate = $_POST['report_date'] ?? null;
        $request->createdBy = $userId ? (int) $userId : null;

        try {
            // 2. Jalankan logika pembaruan di Layer Service
            $this->reportService->update($request);

            // 3. Sukses: Set flash message dan alihkan kembali ke halaman rekap utama
            View::render('User', 'User/Report/edit', [
                'title' => 'Ubah Induk Laporan Harian',
                'current' => 'report',
                'success' => 'Sukses edit, silahkan kembali ke halaman laporan untuk melihat perubahan',
                'report' => $report
            ]);

        } catch (Exception $exception) {
            // 4. Gagal: Tampilkan kembali formulir edit bawa pesan error
            View::render('User', 'User/Report/edit', [
                'title' => 'Ubah Induk Laporan Harian',
                'current' => 'report',
                'error' => $exception->getMessage(),
                'report' => $report
            ]);
        }
    }

    public function postDelete(int $id)
    {
        try {
            // 1. Jalankan proses penghapusan data induk laporan di Layer Service
            $this->reportService->delete($id);

            // 2. Sukses: Set notifikasi sukses hapus
            View::flashMessage("Laporan harian berhasil dihapus beserta seluruh giat di dalamnya!");
            header("Location: /reports");
            exit();

        } catch (Exception $exception) {
            // 3. Gagal: Set notifikasi gagal lalu kembalikan ke dashboard rekap utama
            View::flashMessage("Gagal menghapus laporan: " . $exception->getMessage());
            header("Location: /reports");
            exit();
        }
    }

public function tracking(): void
{
    $category  = $_GET['category']   ?? null;
    $startDate = $_GET['start_date'] ?? null;
    $endDate   = $_GET['end_date']   ?? null;

    $options = [];
    if ($category !== null && $category !== '') {
        $options = $this->reportOptionRepository->findByCategory($category);
    }

    View::render(
        'user',
        'User/Report/tracking',
        [
            'title'      => 'Pelacakan Laporan',
            'current'    => 'tracking',
            'categories' => [
                'target'             => 'SASARAN',
                'activity'           => 'KEGIATAN',
                'personnel_strength' => 'KUAT PERS',
                'location'           => 'LOKASI',
                'person_in_charge'   => 'PENANGGUNG JAWAB',
                'expected_result'    => 'HASIL YANG INGIN DI CAPAI',
            ],
            'selectedCategory' => $category,
            'selectedStart'    => $startDate,
            'selectedEnd'      => $endDate,
            'options'          => $options,
            'result'           => null,
            'error'            => null,
        ]
    );
}

public function postTracking(): void
{
    try {
        $request = new ReportTrackingRequest();

        $request->category = $_POST['category'] ?? '';
        $request->optionId = (int) ($_POST['option_id'] ?? 0);

        $request->startDate = new DateTimeImmutable(
            $_POST['start_date'] ?? ''
        );

        $request->endDate = new DateTimeImmutable(
            $_POST['end_date'] ?? ''
        );

        $result = $this->reportTrackingService->track($request);

        View::render(
            'user',
            'User/Report/tracking',
            [
                'title'      => 'Pelacakan Laporan',
                'current'    => 'tracking',
                'categories' => [
                    'target'             => 'SASARAN',
                    'activity'           => 'KEGIATAN',
                    'personnel_strength' => 'KUAT PERS',
                    'location'           => 'LOKASI',
                    'person_in_charge'   => 'PENANGGUNG JAWAB',
                    'expected_result'    => 'HASIL YANG INGIN DI CAPAI',
                ],
                'options' => $this->reportOptionRepository
                    ->findByCategory($request->category),
                'selectedCategory' => $request->category,
                'selectedStart'    => $_POST['start_date'] ?? null,
                'selectedEnd'      => $_POST['end_date']   ?? null,
                'result'           => $result,
                'error'            => null,
            ]
        );
    } catch (Throwable $e) {
        $category  = $_POST['category']   ?? '';
        $startDate = $_POST['start_date'] ?? null;
        $endDate   = $_POST['end_date']   ?? null;

        $options = $category
            ? $this->reportOptionRepository->findByCategory($category)
            : [];

        View::render(
            'user',
            'User/Report/tracking',
            [
                'title'      => 'Pelacakan Laporan',
                'current'    => 'tracking',
                'categories' => [
                    'target'             => 'SASARAN',
                    'activity'           => 'KEGIATAN',
                    'personnel_strength' => 'KUAT PERS',
                    'location'           => 'LOKASI',
                    'person_in_charge'   => 'PENANGGUNG JAWAB',
                    'expected_result'    => 'HASIL YANG INGIN DI CAPAI',
                ],
                'options'          => $options,
                'selectedCategory' => $category,
                'selectedStart'    => $startDate,
                'selectedEnd'      => $endDate,
                'result'           => null,
                'error'            => $e->getMessage(),
            ]
        );
    }
}

    public function pdf(string $date): void
    {
        try {
            $reportDate = new DateTimeImmutable($date);
        } catch (Exception $e) {
            header("Location: /reports");
            exit();
        }

        $report = $this->reportRepository->findByDate($reportDate);

        if ($report === null) {
            header("Location: /reports");
            exit();
        }

        // =========================
        // CREATOR
        // =========================

        $profile = $this->profileRepository->findByUserId($report->createdBy);

        $creatorName = 'Tidak Diketahui';

        if ($profile !== null && !empty($profile->name)) {
            $creatorName = $profile->name;
        } else {
            $user = $this->userRepository->findById($report->createdBy);

            if ($user !== null) {
                $creatorName = $user->email;
            }
        }

        // =========================
        // TANGGAL
        // =========================

        $formattedDate = $this->formatTanggalIndo(
            $report->reportDate
        );

        // =========================
        // ACTIVITIES
        // =========================

        $allItems = $this->reportItemRepository->findByReportId(
            $report->id
        );

        $activitiesWithNames = [];

        foreach ($allItems as $item) {

            $target = $this->reportOptionRepository->findById(
                $item->targetOptionId
            );

            $activity = $this->reportOptionRepository->findById(
                $item->activityOptionId
            );

            $personnel = $this->reportOptionRepository->findById(
                $item->personnelStrengthOptionId
            );

            $location = $this->reportOptionRepository->findById(
                $item->locationOptionId
            );

            $pic = $this->reportOptionRepository->findById(
                $item->personInChargeOptionId
            );

            $result = $this->reportOptionRepository->findById(
                $item->expectedResultOptionId
            );

            $activitiesWithNames[] = [
                'item' => $item,

                'targetName' =>
                    $target
                    ? $target->name
                    : 'Tidak Diketahui',

                'activityName' =>
                    $activity
                    ? $activity->name
                    : 'Tidak Diketahui',

                'personnelName' =>
                    $personnel
                    ? $personnel->name
                    : 'Tidak Diketahui',

                'locationName' =>
                    $location
                    ? $location->name
                    : 'Tidak Diketahui',

                'picName' =>
                    $pic
                    ? $pic->name
                    : 'Tidak Diketahui',

                'expectedResultName' =>
                    $result
                    ? $result->name
                    : 'Tidak Diketahui',
            ];
        }

        // =========================
        // RENDER HTML
        // =========================

        ob_start();

        $data = [
            'title' => 'Laporan Harian - ' . $formattedDate,
            'report' => $report,
            'formattedDate' => $formattedDate,
            'creatorName' => $creatorName,
            'activities' => $activitiesWithNames,
        ];

        extract($data);

        require __DIR__ . '/../View/User/Report/pdf.php';

        $html = ob_get_clean();

        // =========================
        // GENERATE PDF
        // =========================

        $pdf = $this->reportPdfService->generate($html);

        // =========================
        // FORMAT NAMA FILE
        // =========================

        $dateIndo = $this->formatTanggalIndo(
            $report->reportDate
        );

        $filename = strtolower(
            str_replace(
                [', ', ' '],
                ['-', '-'],
                $dateIndo
            )
        );

        $filename = 'laporan-' . $filename . '.pdf';


        // =========================
        // OUTPUT
        // =========================

        header('Content-Type: application/pdf');

        header(
            'Content-Disposition: inline; filename="' .
            $filename .
            '"'
        );

        header(
            'Content-Length: ' .
            strlen($pdf)
        );

        echo $pdf;

        exit();
    }

    public function pdfRange(
        string|DateTimeImmutable $startDate,
        string|DateTimeImmutable $endDate
    ): void {

        try {

            // =====================================
            // 1. Pastikan menjadi DateTimeImmutable
            // =====================================

            if (!$startDate instanceof \DateTimeImmutable) {
                $start = new \DateTimeImmutable($startDate);
            } else {
                $start = $startDate;
            }


            if (!$endDate instanceof \DateTimeImmutable) {
                $end = new \DateTimeImmutable($endDate);
            } else {
                $end = $endDate;
            }


            // =====================================
            // 2. Validasi tanggal
            // =====================================

            if ($start > $end) {

                header('Location: /reports');

                exit();
            }


            // =====================================
            // 3. Ambil report
            // =====================================

            $reports = $this->reportRepository->findByDateRange(
                $start,
                $end
            );


            if (empty($reports)) {

                header('Location: /reports');

                exit();
            }


            // =====================================
            // 4. Siapkan data report
            // =====================================

            $reportsData = [];


            foreach ($reports as $report) {

                // =================================
                // Creator
                // =================================

                // =================================
                // Creator
                // =================================

                $creatorName = 'User Telah Dihapus';

                if ($report->createdBy !== null) {

                    $profile = $this->profileRepository->findByUserId(
                        (int) $report->createdBy
                    );

                    if ($profile !== null && !empty($profile->name)) {

                        $creatorName = $profile->name;

                    } else {

                        $user = $this->userRepository->findById(
                            (int) $report->createdBy
                        );

                        if ($user !== null) {
                            $creatorName = $user->email;
                        }
                    }
                }


                // =================================
                // Format tanggal
                // =================================

                $formattedDate = $this->formatTanggalIndo(
                    $report->reportDate
                );


                // =================================
                // Activities
                // =================================

                $allItems = $this->reportItemRepository->findByReportId(
                    $report->id
                );


                $activitiesWithNames = [];


                foreach ($allItems as $item) {

                    $target =
                        $this->reportOptionRepository->findById(
                            $item->targetOptionId
                        );


                    $activity =
                        $this->reportOptionRepository->findById(
                            $item->activityOptionId
                        );


                    $personnel =
                        $this->reportOptionRepository->findById(
                            $item->personnelStrengthOptionId
                        );


                    $location =
                        $this->reportOptionRepository->findById(
                            $item->locationOptionId
                        );


                    $pic =
                        $this->reportOptionRepository->findById(
                            $item->personInChargeOptionId
                        );


                    $result =
                        $this->reportOptionRepository->findById(
                            $item->expectedResultOptionId
                        );


                    $activitiesWithNames[] = [

                        'item' => $item,

                        'targetName' =>
                            $target
                            ? $target->name
                            : 'Tidak Diketahui',

                        'activityName' =>
                            $activity
                            ? $activity->name
                            : 'Tidak Diketahui',

                        'personnelName' =>
                            $personnel
                            ? $personnel->name
                            : 'Tidak Diketahui',

                        'locationName' =>
                            $location
                            ? $location->name
                            : 'Tidak Diketahui',

                        'picName' =>
                            $pic
                            ? $pic->name
                            : 'Tidak Diketahui',

                        'expectedResultName' =>
                            $result
                            ? $result->name
                            : 'Tidak Diketahui',
                    ];
                }


                // =================================
                // Simpan report
                // =================================

                $reportsData[] = [

                    'report' => $report,

                    'formattedDate' =>
                        $formattedDate,

                    'creatorName' =>
                        $creatorName,

                    'activities' =>
                        $activitiesWithNames,
                ];
            }


            // =====================================
            // 5. Data untuk view
            // =====================================

            $data = [

                'title' =>
                    'Rekap Laporan Harian',

                'startDate' =>
                    $start,

                'endDate' =>
                    $end,

                'reports' =>
                    $reportsData,
            ];


            // =====================================
            // 6. Render HTML
            // =====================================

            ob_start();


            extract($data);


            require __DIR__ .
                '/../View/User/Report/pdf-range.php';


            $html = ob_get_clean();


            // =====================================
            // 7. Generate PDF
            // =====================================

            $pdf = $this->reportPdfService->generate(
                $html
            );


            // =====================================
            // 8. Pastikan PDF valid
            // =====================================

            if (substr($pdf, 0, 5) !== '%PDF-') {

                http_response_code(500);

                header(
                    'Content-Type: text/plain; charset=UTF-8'
                );

                echo 'PDF tidak valid.';

                exit();
            }


            // =====================================
            // 9. Bersihkan output buffer
            // =====================================

            while (ob_get_level() > 0) {

                ob_end_clean();
            }


            // =====================================
            // 10. Pastikan header belum dikirim
            // =====================================

            if (headers_sent($file, $line)) {

                http_response_code(500);

                header(
                    'Content-Type: text/plain; charset=UTF-8'
                );

                echo 'Headers sudah dikirim sebelumnya.';
                echo PHP_EOL;
                echo 'File: ' . $file;
                echo PHP_EOL;
                echo 'Line: ' . $line;

                exit();
            }


            // =====================================
            // 11. Format filename
            // =====================================

            $startDateIndo = $this->formatTanggalIndo($start);

            $endDateIndo = $this->formatTanggalIndo($end);


            $startFilename = strtolower(
                str_replace(
                    [', ', ' '],
                    ['-', '-'],
                    $startDateIndo
                )
            );


            $endFilename = strtolower(
                str_replace(
                    [', ', ' '],
                    ['-', '-'],
                    $endDateIndo
                )
            );


            $filename =
                'laporan-' .
                $startFilename .
                '-sampai-' .
                $endFilename .
                '.pdf';


            // =====================================
            // 12. Header PDF
            // =====================================

            header(
                'Content-Type: application/pdf'
            );


            header(
                'Content-Disposition: inline; filename="' .
                $filename .
                '"'
            );


            header(
                'Content-Length: ' .
                strlen($pdf)
            );

            // =====================================
            // 13. Output PDF
            // =====================================

            echo $pdf;

            exit();

        } catch (\Throwable $e) {

            // =====================================
            // Error handling
            // =====================================

            while (ob_get_level() > 0) {

                ob_end_clean();
            }


            http_response_code(500);


            header(
                'Content-Type: text/plain; charset=UTF-8'
            );


            echo 'ERROR PDF RANGE';
            echo PHP_EOL;
            echo PHP_EOL;

            echo 'Message: ';
            echo $e->getMessage();

            echo PHP_EOL;

            echo 'File: ';
            echo $e->getFile();

            echo PHP_EOL;

            echo 'Line: ';
            echo $e->getLine();

            echo PHP_EOL;

            echo PHP_EOL;

            echo 'TRACE:';
            echo PHP_EOL;

            echo $e->getTraceAsString();


            exit();
        }
    }

}