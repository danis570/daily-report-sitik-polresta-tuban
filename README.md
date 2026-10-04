
---

## 📄 File 2 — `CHANGELOG.md`

Letakkan di root project.

```markdown
# Changelog

Semua perubahan penting pada project ini akan didokumentasikan dalam file ini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan project ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned
- Test coverage untuk middleware
- Test coverage untuk controller
- Notifikasi email
- Export CSV

## [1.0.0] - 2026-10-04

### Added
- **Fitur Duplikat Laporan** — duplikat laporan beserta semua item giat ke tanggal baru
  - Modal konfirmasi dengan date picker
  - Validasi tanggal belum dipakai
  - `createdBy` otomatis diisi user yang melakukan duplikat
  - Copy semua item giat (target, activity, personnel, location, PIC, expected result)

- **Nomor Giat Manual** — user dapat mengatur nomor urut giat sendiri
  - Input manual untuk nomor giat
  - Validasi anti-duplikat dalam 1 laporan
  - Validasi minimal 1
  - Validasi wajib diisi
  - Default nomor otomatis = max + 1

- **Quick-Add Opsi** — tambah opsi baru langsung dari form input
  - Tombol "+ Tambah" saat dropdown kosong
  - Modal quick-add dengan nama & kategori
  - AJAX endpoint `/report/option/quick-add`
  - Auto-select opsi baru setelah disimpan
  - Deteksi duplikat — kalau sudah ada, langsung pilih existing

- **Validasi Hapus Opsi** — cegah hapus opsi yang masih dipakai
  - Cek pemakaian di `report_items`
  - Pesan error menyertakan jumlah pemakaian
  - Metode `countUsageByOptionId()` & `findUsageDetailsByOptionId()`

- **Dark Mode Lengkap** — toggle dark mode di semua halaman
  - Tersimpan di localStorage
  - Mengikuti preferensi sistem
  - Semua komponen support dark mode

- **Layout Sidebar** — navigasi sidebar untuk user & admin
  - Sidebar fixed di kiri
  - Mobile top bar dengan tombol menu
  - Slide-in sidebar untuk mobile
  - Overlay backdrop

- **Breadcrumb Navigation** — navigasi breadcrumb di halaman detail
  - Laporan → Tanggal → Edit
  - Laporan → Tanggal → Tambah Giat

- **Modal Konfirmasi Hapus** — modal custom pengganti `confirm()`
  - Styling konsisten dengan tema brutalist
  - Bisa ditutup dengan ESC / klik backdrop
  - Untuk hapus laporan, item giat, dan opsi

- **Validasi Rentang Tanggal** — maksimal 31 hari filter
  - Client-side & server-side
  - Validasi `end >= start`
  - Tombol Tampilkan & Cetak PDF di-disable kalau tanggal belum lengkap

- **Realtime Search** — pencarian realtime di daftar laporan
  - Filter berdasarkan tanggal, pembuat, ID
  - Tombol clear (×)

- **Flash Message** — notifikasi sukses & error
  - Tipe `success` (hijau) & `error` (merah)
  - Tombol close
  - Auto-dismiss (opsional)

- **Testing** — 139 unit test dengan 330 assertions
  - Repository test untuk `ReportItemRepository`, `ReportOptionRepository`, `ReportRepository`
  - Service test untuk `ReportService`, `ReportItemService`, `ReportOptionService`, `UserService`
  - Coverage fitur baru (duplikat, itemNo manual, hapus opsi)

### Changed
- Layout admin & user dari navbar horizontal menjadi **sidebar**
- Header public diperbesar untuk desktop
- Mobile top bar diperkecil dan dirapikan
- Style flash message jadi mendukung tipe (success/error)
- Semua form disesuaikan dengan dark mode
- Validasi form lebih ketat (client-side & server-side)
- Timeout session diperbaiki — panggil `session_start()` di bootstrap

### Fixed
- Bug: dropdown hilang setelah tutup modal validasi
- Bug: option baru tidak bisa diklik setelah quick-add
- Bug: option baru tidak ter-filter setelah quick-add
- Bug: session tidak tersimpan di `postDuplicate()`
- Bug: FK constraint error saat duplikat dengan `createdBy` invalid
- Bug: `flash_message` selalu hijau meski error
- Bug: `theme-toggle-mobile` null di halaman tertentu
- Bug: modal tidak tertutup setelah duplikat sukses

### Security
- Password hashing dengan `PASSWORD_BCRYPT`
- Validasi MIME type untuk upload avatar
- Batas ukuran file upload 2MB
- CSRF protection di form (kalau ada)
- Session guard di middleware — handle null session
- Clear cookie saat session invalid