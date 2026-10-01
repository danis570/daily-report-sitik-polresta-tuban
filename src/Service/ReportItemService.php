<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportItem;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportItemRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportItemResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportItemRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportItemResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportItemRepository;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;

class ReportItemService
{
    private ReportItemRepository $reportItemRepository;
    private ReportRepository $reportRepository;

    public function __construct(ReportItemRepository $reportItemRepository, ReportRepository $reportRepository)
    {
        $this->reportItemRepository = $reportItemRepository;
        $this->reportRepository = $reportRepository;
    }

    /**
     * @param UserAddReportItemRequest $request
     * @return UserAddReportItemResponse
     * @throws Exception
     */
    public function create(UserAddReportItemRequest $request): UserAddReportItemResponse
    {
        // 1. Validasi Input Dasar
        $this->createValidation($request);

        // 2. Hitung item_no otomatis untuk laporan bersangkutan
        $currentCount = $this->reportItemRepository->countByReportId($request->reportId);
        $nextItemNo = $currentCount + 1;

        $reportItem = new ReportItem();
        $reportItem->reportId = $request->reportId;
        $reportItem->itemNo = $nextItemNo;
        $reportItem->targetOptionId = $request->targetOptionId;
        $reportItem->activityOptionId = $request->activityOptionId;
        $reportItem->personnelStrengthOptionId = $request->personnelStrengthOptionId;
        $reportItem->locationOptionId = $request->locationOptionId;
        $reportItem->personInChargeOptionId = $request->personInChargeOptionId;
        $reportItem->expectedResultOptionId = $request->expectedResultOptionId;
        $reportItem->remarks = trim($request->remarks);

        // 4. Simpan ke database melalui Repository
        $savedItem = $this->reportItemRepository->save($reportItem);

        // 5. Kembalikan Response DTO
        $response = new UserAddReportItemResponse();
        $response->reportItem = $savedItem;

        return $response;
    }

    /**
     * @param UserAddReportItemRequest $request
     * @return void
     * @throws Exception
     */
    private function createValidation(UserAddReportItemRequest $request): void
    {
        if ($request->reportId === null) {
            throw new Exception("Laporan induk tidak valid.");
        }

        // Pastikan laporan induk benar-benar ada di database
        $report = $this->reportRepository->findById($request->reportId);
        if ($report === null) {
            throw new Exception("Laporan utama tidak ditemukan.");
        }

        // Validasi semua opsi wajib diisi
        if (empty($request->targetOptionId))
            throw new Exception("Sasaran/Target wajib dipilih.");
        if (empty($request->activityOptionId))
            throw new Exception("Jenis Kegiatan wajib dipilih.");
        if (empty($request->personnelStrengthOptionId))
            throw new Exception("Kuat Personel wajib dipilih.");
        if (empty($request->locationOptionId))
            throw new Exception("Lokasi Giat wajib dipilih.");
        if (empty($request->personInChargeOptionId))
            throw new Exception("Perwira Penanggung Jawab wajib dipilih.");
        if (empty($request->expectedResultOptionId))
            throw new Exception("Hasil yang Diharapkan wajib dipilih.");
    }

      public function update(UserUpdateReportItemRequest $request): UserUpdateReportItemResponse
    {
        // 1. Jalankan fungsi validasi khusus untuk update
        $this->updateValidation($request);

        // 2. Ambil data asli entitas domain dari database
        $reportItem = $this->reportItemRepository->findById($request->id);

        // 3. Petakan perubahan data baru (Nilai reportId dan itemNo TIDAK BOLEH diubah)
        $reportItem->targetOptionId = $request->targetOptionId;
        $reportItem->activityOptionId = $request->activityOptionId;
        $reportItem->personnelStrengthOptionId = $request->personnelStrengthOptionId;
        $reportItem->locationOptionId = $request->locationOptionId;
        $reportItem->personInChargeOptionId = $request->personInChargeOptionId;
        $reportItem->expectedResultOptionId = $request->expectedResultOptionId;
        $reportItem->remarks = trim($request->remarks);

        // 4. Perbarui ke database via repository
        $this->reportItemRepository->update($reportItem);

        // 5. Kembalikan Response DTO
        $response = new UserUpdateReportItemResponse();
        $response->reportItem = $reportItem;

        return $response;
    }

    public function delete(int $id): void
    {
        // Pastikan datanya memang terdaftar sebelum dihapus
        $existingItem = $this->reportItemRepository->findById($id);
        if ($existingItem === null) {
            throw new Exception("Item kegiatan laporan tidak ditemukan atau sudah dihapus.");
        }

        $this->reportItemRepository->deleteById($id);
    }

    private function updateValidation(UserUpdateReportItemRequest $request): void
    {
        if ($request->id === null) {
            throw new Exception("ID rincian kegiatan tidak valid.");
        }

        // Ambil entitas asli untuk memastikan keberadaannya
        $currentItem = $this->reportItemRepository->findById($request->id);
        if ($currentItem === null) {
            throw new Exception("Rincian kegiatan laporan tidak ditemukan.");
        }

        // Validasi seluruh input pilihan wajib terisi
        if (empty($request->targetOptionId)) throw new Exception("Sasaran/Target wajib dipilih.");
        if (empty($request->activityOptionId)) throw new Exception("Jenis Kegiatan wajib dipilih.");
        if (empty($request->personnelStrengthOptionId)) throw new Exception("Kuat Personel wajib dipilih.");
        if (empty($request->locationOptionId)) throw new Exception("Lokasi Giat wajib dipilih.");
        if (empty($request->personInChargeOptionId)) throw new Exception("Perwira Penanggung Jawab wajib dipilih.");
        if (empty($request->expectedResultOptionId)) throw new Exception("Hasil yang Diharapkan wajib dipilih.");
    }
}
