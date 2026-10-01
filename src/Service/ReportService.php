<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use DateTimeImmutable;
use Exception;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\Report;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserAddReportResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportRequest;
use Unirow2026\DailyReportSitikPolrestaTuban\Model\Report\UserUpdateReportResponse;
use Unirow2026\DailyReportSitikPolrestaTuban\Repository\ReportRepository;

class ReportService
{
    private ReportRepository $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function create(UserAddReportRequest $request): UserAddReportResponse
    {
        $reportDate = $this->createValidation($request);

        $report = new Report();
        $report->reportDate = $reportDate;
        $report->createdBy = $request->createdBy;

        $savedReport = $this->reportRepository->save($report);

        $response = new UserAddReportResponse();
        $response->report = $savedReport;

        return $response;
    }

    private function createValidation(UserAddReportRequest $request): DateTimeImmutable
    {
        if ($request->reportDate === null || trim($request->reportDate) === "") {
            throw new Exception("Tanggal laporan tidak boleh kosong.");
        }

        if ($request->createdBy === null) {
            throw new Exception("Identitas pembuat laporan tidak valid.");
        }

        try {
            $reportDate = new DateTimeImmutable($request->reportDate);
        } catch (Exception $e) {
            throw new Exception("Format tanggal laporan tidak valid.");
        }

        $existingReport = $this->reportRepository->findByDate($reportDate);
        if ($existingReport !== null) {
            throw new Exception("Laporan untuk tanggal " . $reportDate->format('d-m-Y') . " sudah pernah dibuat.");
        }

        return $reportDate;
    }

      public function update(UserUpdateReportRequest $request): UserUpdateReportResponse
    {
        // 1. Eksekusi validasi input dan aturan bisnis kembar
        $reportDate = $this->updateValidation($request);

        // 2. Ambil objek entitas domain murni dari database
        $report = $this->reportRepository->findById($request->id);

        // 3. Petakan perubahan data baru
        $report->reportDate = $reportDate;
        $report->createdBy = $request->createdBy;

        // 4. Lakukan pembaruan via repository
        $this->reportRepository->update($report);

        // 5. Kembalikan Response DTO
        $response = new UserUpdateReportResponse();
        $response->report = $report;

        return $response;
    }


    public function delete(int $id): void
    {
        // Pastikan laporan induk terdaftar di sistem sebelum dieksekusi hapus
        $existingReport = $this->reportRepository->findById($id);
        if ($existingReport === null) {
            throw new Exception("Data laporan harian tidak ditemukan atau sudah dihapus.");
        }

        $this->reportRepository->deleteById($id);
    }


    private function updateValidation(UserUpdateReportRequest $request): DateTimeImmutable
    {
        if ($request->id === null) {
            throw new Exception("ID laporan tidak valid.");
        }

        if ($request->reportDate === null || trim($request->reportDate) === "") {
            throw new Exception("Tanggal laporan tidak boleh kosong.");
        }

        if ($request->createdBy === null) {
            throw new Exception("Identitas pembuat laporan tidak valid.");
        }

        // Pastikan laporan lama eksis di database
        $currentReport = $this->reportRepository->findById($request->id);
        if ($currentReport === null) {
            throw new Exception("Laporan yang ingin diubah tidak ditemukan.");
        }

        try {
            $reportDate = new DateTimeImmutable($request->reportDate);
        } catch (Exception $e) {
            throw new Exception("Format tanggal laporan tidak valid.");
        }

        // Aturan Bisnis: Mencegah merubah tanggal ke tanggal yang sudah pernah dibuat oleh orang lain
        $existingReport = $this->reportRepository->findByDate($reportDate);
        if ($existingReport !== null && $existingReport->id !== $request->id) {
            throw new Exception("Laporan untuk tanggal " . $reportDate->format('d-m-Y') . " sudah pernah dibuat.");
        }

        return $reportDate;
    }

}
