<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportOptionRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportOptionResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;

class ReportOptionServiceTest extends TestCase
{
    private ReportOptionService $reportOptionService;
    private ReportOptionRepository $reportOptionRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->reportOptionRepository = new ReportOptionRepository($connection);
        $this->reportOptionService = new ReportOptionService($this->reportOptionRepository);

        // Bersihkan data table sebelum tiap pengujian berjalan
        $this->reportOptionRepository->deleteAll();
    }

    public function testCreateSuccess()
    {
        $request = new UserAddReportOptionRequest();
        $request->category = 'Giat';
        $request->name = 'Patroli Sinergitas';
        $request->description = 'Aktivitas patroli bersama TNI-Polri';

        $response = $this->reportOptionService->create($request);

        self::assertInstanceOf(UserAddReportOptionResponse::class, $response);
        self::assertNotNull($response->reportOption->id);
        self::assertEquals('Giat', $response->reportOption->category);
        self::assertEquals('Patroli Sinergitas', $response->reportOption->name);
        self::assertEquals('Aktivitas patroli bersama TNI-Polri', $response->reportOption->description);
    }

    public function testCreateCategoryEmpty()
    {
        $request = new UserAddReportOptionRequest();
        $request->category = ''; // Kategori Kosong
        $request->name = 'Patroli';
        $request->description = 'Deskripsi';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Kategori pilihan laporan tidak boleh kosong.');

        $this->reportOptionService->create($request);
    }

    public function testCreateNameEmpty()
    {
        $request = new UserAddReportOptionRequest();
        $request->category = 'Giat';
        $request->name = '   '; // Nama Kosong / Spasi saja
        $request->description = 'Deskripsi';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Nama pilihan laporan tidak boleh kosong.');

        $this->reportOptionService->create($request);
    }

    public function testCreateDuplicateCategoryAndName()
    {
        // 1. Simpan data pertama
        $request1 = new UserAddReportOptionRequest();
        $request1->category = 'Giat';
        $request1->name = 'Patroli';
        $request1->description = 'Deskripsi awal';
        $this->reportOptionService->create($request1);

        // 2. Coba simpan data kedua dengan Kategori dan Nama yang sama persis
        $request2 = new UserAddReportOptionRequest();
        $request2->category = 'Giat';
        $request2->name = 'Patroli';
        $request2->description = 'Deskripsi berbeda';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Pilihan 'Patroli' sudah terdaftar pada kategori 'Giat'.");

        $this->reportOptionService->create($request2);
    }

        public function testUpdateSuccess()
    {
        // 1. Buat data awal dulu
        $option = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption();
        $option->category = 'Giat';
        $option->name = 'Patroli';
        $option->description = 'Deskripsi Lama';
        $savedOption = $this->reportOptionRepository->save($option);

        // 2. Kirim request update
        $request = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportOptionRequest();
        $request->id = $savedOption->id;
        $request->category = 'Giat Baru';
        $request->name = 'Patroli Baru';
        $request->description = 'Deskripsi Baru';

        $response = $this->reportOptionService->update($request);

        // 3. Asersi perubahan
        self::assertEquals('Giat Baru', $response->reportOption->category);
        self::assertEquals('Patroli Baru', $response->reportOption->name);
        self::assertEquals('Deskripsi Baru', $response->reportOption->description);
    }

    public function testUpdateDuplicateName()
    {
        // 1. Buat data pertama
        $option1 = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption();
        $option1->category = 'Giat';
        $option1->name = 'Patroli';
        $this->reportOptionRepository->save($option1);

        // 2. Buat data kedua
        $option2 = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption();
        $option2->category = 'Giat';
        $option2->name = 'Penjagaan';
        $savedOption2 = $this->reportOptionRepository->save($option2);

        // 3. Coba ubah data kedua menjadi 'Patroli' (bakal kembar dengan data pertama)
        $request = new \Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportOptionRequest();
        $request->id = $savedOption2->id;
        $request->category = 'Giat';
        $request->name = 'Patroli'; // Memicu Exception

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Pilihan 'Patroli' sudah terdaftar pada kategori 'Giat'.");

        $this->reportOptionService->update($request);
    }

    public function testDeleteSuccess()
    {
        // 1. Buat data yang mau dihapus
        $option = new \Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption();
        $option->category = 'Giat';
        $option->name = 'Hapus Aku';
        $savedOption = $this->reportOptionRepository->save($option);

        // 2. Jalankan fungsi hapus di Service
        $this->reportOptionService->delete($savedOption->id);

        // 3. Pastikan saat dicari lagi hasilnya null
        $result = $this->reportOptionRepository->findById($savedOption->id);
        self::assertNull($result);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Pilihan laporan tidak ditemukan atau sudah dihapus.");

        // Coba hapus ID asal yang tidak terdaftar (misal ID: 999)
        $this->reportOptionService->delete(999);
    }

}
