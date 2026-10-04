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
        // 1. Validasi input dasar
        $this->createValidation($request);

        // 2. Gunakan itemNo dari request (manual dari user)
        $reportItem = new ReportItem();
        $reportItem->reportId = $request->reportId;
        $reportItem->itemNo = (int) $request->itemNo;   // ← dari user
        $reportItem->targetOptionId = $request->targetOptionId;
        $reportItem->activityOptionId = $request->activityOptionId;
        $reportItem->personnelStrengthOptionId = $request->personnelStrengthOptionId;
        $reportItem->locationOptionId = $request->locationOptionId;
        $reportItem->personInChargeOptionId = $request->personInChargeOptionId;
        $reportItem->expectedResultOptionId = $request->expectedResultOptionId;
        $reportItem->remarks = trim($request->remarks ?? '');

        // 3. Simpan ke database
        $savedItem = $this->reportItemRepository->save($reportItem);

        // 4. Kembalikan Response DTO
        $response = new UserAddReportItemResponse();
        $response->reportItem = $savedItem;

        return $response;
    }

    private function createValidation(UserAddReportItemRequest $request): void
    {
        if ($request->reportId === null) {
            throw new Exception("Laporan induk tidak valid.");
        }

        // Pastikan laporan induk ada
        $report = $this->reportRepository->findById($request->reportId);
        if ($report === null) {
            throw new Exception("Laporan utama tidak ditemukan.");
        }

        // ✅ Validasi itemNo
        if ($request->itemNo === null || $request->itemNo === '') {
            throw new Exception("Nomor giat wajib diisi.");
        }

        if ((int) $request->itemNo < 1) {
            throw new Exception("Nomor giat minimal 1.");
        }

        // ✅ Cek duplikat nomor di laporan yang sama
        $existingItems = $this->reportItemRepository
            ->findByReportId((int) $request->reportId);

        foreach ($existingItems as $existing) {
            if ((int) $existing->itemNo === (int) $request->itemNo) {
                throw new Exception(
                    "Nomor giat #" . $request->itemNo . " sudah dipakai. " .
                    "Silakan pilih nomor lain."
                );
            }
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
    // 1. Validasi
    $this->updateValidation($request);

    // 2. Ambil data asli
    $reportItem = $this->reportItemRepository->findById($request->id);

    // 3. Update semua field (termasuk itemNo, reportId tetap)
    $reportItem->itemNo                    = (int) $request->itemNo;   // ← DIIZINKAN UBAH
    $reportItem->targetOptionId            = $request->targetOptionId;
    $reportItem->activityOptionId          = $request->activityOptionId;
    $reportItem->personnelStrengthOptionId = $request->personnelStrengthOptionId;
    $reportItem->locationOptionId          = $request->locationOptionId;
    $reportItem->personInChargeOptionId    = $request->personInChargeOptionId;
    $reportItem->expectedResultOptionId    = $request->expectedResultOptionId;
    $reportItem->remarks                   = trim($request->remarks ?? '');

    // 4. Simpan ke database
    $this->reportItemRepository->update($reportItem);

    // 5. Response
    $response = new UserUpdateReportItemResponse();
    $response->reportItem = $reportItem;

    return $response;
}

    private function updateValidation(UserUpdateReportItemRequest $request): void
{
    if ($request->id === null) {
        throw new Exception("ID rincian kegiatan tidak valid.");
    }

    // Cek item ada
    $currentItem = $this->reportItemRepository->findById($request->id);
    if ($currentItem === null) {
        throw new Exception("Rincian kegiatan laporan tidak ditemukan.");
    }

    // ✅ Validasi itemNo
    if ($request->itemNo === null || $request->itemNo === '') {
        throw new Exception("Nomor giat wajib diisi.");
    }

    if ((int) $request->itemNo < 1) {
        throw new Exception("Nomor giat minimal 1.");
    }

    // ✅ Cek duplikat nomor di laporan yang sama (kecuali dirinya sendiri)
    $existingItems = $this->reportItemRepository
        ->findByReportId($currentItem->reportId);

    foreach ($existingItems as $other) {
        if ((int) $other->id !== (int) $request->id
            && (int) $other->itemNo === (int) $request->itemNo) {
            throw new Exception(
                "Nomor giat #" . $request->itemNo . " sudah dipakai oleh item lain. " .
                "Silakan pilih nomor lain."
            );
        }
    }

    // Validasi opsi wajib
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

    public function delete(int $id): void
    {
        // Pastikan datanya memang terdaftar sebelum dihapus
        $existingItem = $this->reportItemRepository->findById($id);
        if ($existingItem === null) {
            throw new Exception("Item kegiatan laporan tidak ditemukan atau sudah dihapus.");
        }

        $this->reportItemRepository->deleteById($id);
    }

    
}
