<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportOptionRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportOptionResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportOptionRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportOptionResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportOptionRepository;


class ReportOptionService
{
    private ReportOptionRepository $reportOptionRepository;
    private ReportItemRepository $reportItemRepository;

    public function __construct(ReportOptionRepository $reportOptionRepository, ReportItemRepository $reportItemRepository)
    {
        $this->reportOptionRepository = $reportOptionRepository;
        $this->reportItemRepository = $reportItemRepository;
    }

    public function create(UserAddReportOptionRequest $request): UserAddReportOptionResponse
    {
        // 1. Validasi input aplikasi
        $this->createValidation($request);

        // 2. Mapping data dari Request DTO ke Domain Entity
        $reportOption = new ReportOption();
        $reportOption->category = trim($request->category);
        $reportOption->name = trim($request->name);
        $reportOption->description = $request->description !== null ? trim($request->description) : null;

        // 3. Simpan data ke database melalui Repository
        $savedOption = $this->reportOptionRepository->save($reportOption);

        // 4. Siapkan objek Response DTO
        $response = new UserAddReportOptionResponse();
        $response->reportOption = $savedOption;

        return $response;
    }

    private function createValidation(UserAddReportOptionRequest $request): void
    {
        // Cek jika kategori kosong
        if ($request->category === null || trim($request->category) === "") {
            throw new Exception("Kategori pilihan laporan tidak boleh kosong.");
        }

        // Cek jika nama pilihan kosong
        if ($request->name === null || trim($request->name) === "") {
            throw new Exception("Nama pilihan laporan tidak boleh kosong.");
        }

        // Aturan Bisnis: Mencegah nama pilihan yang sama dalam satu kategori yang sama
        $existingOption = $this->reportOptionRepository->findByCategoryAndName(
            trim($request->category),
            trim($request->name)
        );

        if ($existingOption !== null) {
            throw new Exception("Pilihan '" . htmlspecialchars($request->name) . "' sudah terdaftar pada kategori '" . htmlspecialchars($request->category) . "'.");
        }
    }

    public function update(UserUpdateReportOptionRequest $request): UserUpdateReportOptionResponse
    {
        // 1. Validasi input dan aturan bisnis
        $this->updateValidation($request);

        // 2. Ambil data asli dari database
        $reportOption = $this->reportOptionRepository->findById($request->id);

        // 3. Update properti objek domain
        $reportOption->category = trim($request->category);
        $reportOption->name = trim($request->name);
        $reportOption->description = $request->description !== null ? trim($request->description) : null;

        // 4. Jalankan perintah update ke database
        $this->reportOptionRepository->update($reportOption);

        // 5. Kembalikan Response DTO
        $response = new UserUpdateReportOptionResponse();
        $response->reportOption = $reportOption;

        return $response;
    }

    public function delete(int $id): void
    {
        // 1. Cek dulu apakah datanya ada sebelum dihapus
        $existingOption = $this->reportOptionRepository->findById($id);

        if ($existingOption === null) {
            throw new Exception("Pilihan laporan tidak ditemukan atau sudah dihapus.");
        }

        // 2. Cek apakah opsi masih dipakai di report_items
        $usageCount = $this->reportItemRepository->countUsageByOptionId($id);

        if ($usageCount > 0) {
            throw new Exception(
                "Pilihan laporan tidak dapat dihapus karena masih digunakan pada " .
                $usageCount . " rincian giat. " .
                "Hapus atau ubah rincian giat yang menggunakan opsi ini terlebih dahulu."
            );
        }

        // 3. Aman dihapus
        $this->reportOptionRepository->deleteById($id);
    }

    private function updateValidation(UserUpdateReportOptionRequest $request): void
    {
        if ($request->id === null) {
            throw new Exception("ID pilihan laporan tidak valid.");
        }

        if ($request->category === null || trim($request->category) === "") {
            throw new Exception("Kategori pilihan laporan tidak boleh kosong.");
        }

        if ($request->name === null || trim($request->name) === "") {
            throw new Exception("Nama pilihan laporan tidak boleh kosong.");
        }

        // Pastikan data yang mau di-update memang ada
        $currentOption = $this->reportOptionRepository->findById($request->id);
        if ($currentOption === null) {
            throw new Exception("Pilihan laporan yang ingin diubah tidak ditemukan.");
        }

        $existingOption = $this->reportOptionRepository->findByCategoryAndName(
            trim($request->category),
            trim($request->name)
        );

        if ($existingOption !== null && $existingOption->id !== $request->id) {
            throw new Exception("Pilihan '" . htmlspecialchars($request->name) . "' sudah terdaftar pada kategori '" . htmlspecialchars($request->category) . "'.");
        }
    }
}