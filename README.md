# SITIK Polresta Tuban — Daily Report System

Sistem Informasi Laporan Harian TIK Polresta Tuban. Aplikasi web untuk mengelola laporan harian kegiatan SITIK beserta rincian giat, opsi jawaban dinamis, dan pelacakan penggunaan opsi.

**Versi:** 1.0.0 · **PHP:** 8.5+ · **License:** MIT

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Struktur Project](#struktur-project)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Testing](#testing)
- [Daftar Endpoint](#daftar-endpoint)
- [Changelog](#changelog)
- [Cara Release](#cara-release)
- [Lisensi](#lisensi)

---

## Fitur Utama

### Autentikasi & Keamanan
- Login/logout dengan session-based authentication
- Role-based access (admin & user)
- Middleware proteksi halaman
- Password hashing dengan bcrypt

### Manajemen Laporan Harian
- Buat, edit, hapus, dan duplikat laporan harian
- Setiap laporan berisi **rincian giat** (item kegiatan) yang dapat dikelola
- **Nomor giat manual** — user dapat mengatur nomor urut sesuai kebutuhan
- Validasi anti-duplikat nomor giat (create & update)
- **Duplikat laporan** ke tanggal baru beserta semua item giatnya

### Opsi Jawaban Dinamis
- Kelola opsi jawaban berdasarkan kategori:
  - Sasaran / Target
  - Kegiatan / Aktivitas
  - Kuat Personel
  - Lokasi
  - Penanggung Jawab
  - Hasil yang Diharapkan
- **Quick-add** opsi baru langsung dari form input (AJAX)
- Validasi anti-hapus untuk opsi yang masih dipakai
- Pelacakan jumlah penggunaan setiap opsi

### Pelacakan & Pelaporan
- Lacak penggunaan opsi dalam rentang tanggal tertentu
- Filter laporan berdasarkan rentang tanggal (maks 31 hari)
- Export/cetak laporan ke PDF (single & range)
- Pencarian realtime pada daftar laporan

### Manajemen User
- Daftar seluruh pengguna sistem
- Tambah user baru via form register
- Hapus user (dengan proteksi untuk akun admin)
- Edit profil (nama & avatar) dengan upload file

### Antarmuka
- Desain **neo-brutalism** — border tebal, shadow keras, warna kontras
- **Dark mode** lengkap di semua halaman
- Responsive — desktop, tablet, mobile
- Sidebar navigasi untuk user & admin
- Flash message dengan tipe (success/error)
- Modal konfirmasi hapus (custom, bukan `confirm()`)

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend** | PHP 8.5 |
| **Database** | MySQL / MariaDB |
| **Frontend** | Tailwind CSS (CDN) + Lucide Icons |
| **Font** | Archivo Black + Space Grotesk |
| **Testing** | PHPUnit 13 |
| **Arsitektur** | MVC + Repository + Service Layer |

---

## Struktur Project

```
daily-report-sitik-polresta-tuban/
├── config/                     # Konfigurasi aplikasi & database
├── public/                     # Web root
│   ├── index.php               # Front controller
│   ├── assets/                 # Logo, favicon
│   └── uploads/avatar/         # Upload avatar user
├── src/
│   ├── App/                    # Core (Database, View, BaseController)
│   ├── Controller/             # Controller (HTTP handler)
│   ├── Domain/                 # Entity (Report, ReportItem, dll)
│   ├── Middleware/             # Auth guard
│   ├── Model/                  # DTO Request/Response
│   ├── Repository/             # Akses database
│   ├── Service/                # Business logic
│   └── View/                   # Template (User, Admin, Public)
├── tests/                      # Unit test (PHPUnit)
│   ├── Repository/
│   └── Service/
├── vendor/                     # Composer dependencies
├── composer.json
├── composer.lock
└── README.md
```

---

## Instalasi

### Prasyarat
- PHP **8.5+**
- Composer
- MySQL / MariaDB
- Web server (Apache / Nginx) atau PHP built-in server

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/username/daily-report-sitik-polresta-tuban.git
cd daily-report-sitik-polresta-tuban
```

**2. Install dependency**
```bash
composer install
```

**3. Setup database**

Buat database baru:
```sql
CREATE DATABASE daily_report_tik_polresta_tuban_db;
```

Import dump database:
```bash
mysql -u root -p daily_report_tik_polresta_tuban_db < dump-daily_report_tik_polresta_tuban_db.sql
```

**4. Konfigurasi database**

Edit file `config/database.php` sesuaikan dengan setup lokal:
```php
return [
    'host'     => 'localhost',
    'database' => 'daily_report_tik_polresta_tuban_db',
    'username' => 'root',
    'password' => '',
];
```

**5. Jalankan aplikasi**

Menggunakan PHP built-in server:
```bash
cd public
php -S localhost:8000
```

Atau via Apache/XAMPP:
```
http://localhost/daily-report-sitik-polresta-tuban/public
```

**6. Login**

Buka `http://localhost:8000` dan login dengan akun yang sudah didaftarkan.

---

## Konfigurasi

### Database

Konfigurasi koneksi database ada di `config/database.php`. Support multi-environment (dev, prod, test).

### Session

Session di-start di `public/index.php` — sebelum autoload apapun. Guard `PHP_SESSION_NONE` mencegah double-start.

### Upload Avatar

- Lokasi: `public/uploads/avatar/`
- Format: `jpg`, `jpeg`, `png`, `webp`
- Maks ukuran: **2 MB**
- Validasi MIME type dengan `finfo`

---

## Testing

Jalankan seluruh test suite:
```bash
vendor/bin/phpunit tests
```

**Expected output:**
```
OK (139 tests, 330 assertions)
```

### Jalankan Test Spesifik

```bash
# Per file
vendor/bin/phpunit tests/Service/ReportServiceTest.php
vendor/bin/phpunit tests/Repository/ReportItemRepositoryTest.php

# Per direktori
vendor/bin/phpunit tests/Service
vendor/bin/phpunit tests/Repository

# Filter by nama test
vendor/bin/phpunit --filter testDuplicate tests
vendor/bin/phpunit --filter testDelete tests/Service/ReportOptionServiceTest.php
```

### Coverage Test

Butuh Xdebug terinstall. Cek dulu:
```bash
php -m | grep xdebug
```

Kalau ada, jalankan:
```bash
vendor/bin/phpunit tests --coverage-html coverage/
```

Buka `coverage/index.html` di browser untuk lihat persentase coverage.

---

## Daftar Endpoint

### Auth
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/login` | Form login |
| POST | `/login` | Proses login |
| GET | `/register` | Form register (admin) |
| POST | `/register` | Simpan user baru |
| GET | `/logout` | Logout user |

### Laporan
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/reports` | Daftar laporan (10 terbaru, filter maks 31 hari) |
| GET | `/report/add` | Form tambah laporan |
| POST | `/report/add` | Simpan laporan baru |
| GET | `/report/{date}` | Detail laporan |
| GET | `/report/edit/{id}` | Form edit laporan |
| POST | `/report/edit/{id}` | Update laporan |
| POST | `/report/delete/{id}` | Hapus laporan |
| POST | `/report/duplicate/{id}` | Duplikat laporan + semua item |

### Item Giat
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/report/item/{date}/add` | Form tambah item giat |
| POST | `/report/item/{date}/add` | Simpan item giat |
| GET | `/report/item/edit/{id}` | Form edit item giat |
| POST | `/report/item/edit/{id}` | Update item giat |
| POST | `/report/item/delete/{id}` | Hapus item giat |

### Opsi Jawaban
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/report/options` | Daftar opsi (dengan badge pemakaian) |
| GET | `/report/option/add` | Form tambah opsi |
| POST | `/report/option/add` | Simpan opsi baru |
| GET | `/report/option/edit/{id}` | Form edit opsi |
| POST | `/report/option/edit/{id}` | Update opsi |
| POST | `/report/option/delete/{id}` | Hapus opsi (validasi pemakaian) |
| POST | `/report/option/quick-add` | AJAX quick-add (return JSON) |

### Pelacakan
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/report/tracking` | Form pelacakan opsi |
| POST | `/report/tracking` | Proses pelacakan |

### Cetak PDF
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/report/print/pdf/{date}` | Cetak 1 tanggal |
| GET | `/report/print/pdf/{start}/{end}` | Cetak rentang |

### User (Admin)
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/users` | Daftar user |
| POST | `/user/delete` | Hapus user |

### Profil
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/profile` | Form edit profil |
| POST | `/profile/update` | Update profil + upload avatar |

### Public
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/` | Landing page |
| GET | `/about` | Halaman about |

---

## Changelog

Semua perubahan penting pada project ini didokumentasikan di sini. Format mengikuti [Keep a Changelog](https://keepachangelog.com/) dan [Semantic Versioning](https://semver.org/).

### [Unreleased]

**Planned:**
- Test coverage untuk middleware
- Test coverage untuk controller
- Notifikasi email
- Export CSV

### [1.0.0] — 2026-10-04

**Added:**

- **Fitur Duplikat Laporan** — duplikat laporan beserta semua item giat ke tanggal baru
  - Modal konfirmasi dengan date picker
  - Validasi tanggal belum dipakai
  - `createdBy` otomatis diisi user yang melakukan duplikat
  - Copy semua item giat (target, activity, personnel, location, PIC, expected result)

- **Nomor Giat Manual** — user dapat mengatur nomor urut giat sendiri
  - Input manual untuk nomor giat
  - Validasi anti-duplikat dalam 1 laporan
  - Validasi minimal 1 & wajib diisi
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
  - Method `countUsageByOptionId()` & `findUsageDetailsByOptionId()`

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
  - `Laporan → Tanggal → Edit`
  - `Laporan → Tanggal → Tambah Giat`

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
  - Repository test & Service test
  - Coverage fitur baru (duplikat, itemNo manual, hapus opsi)

**Changed:**

- Layout admin & user dari navbar horizontal menjadi **sidebar**
- Header public diperbesar untuk desktop
- Mobile top bar diperkecil dan dirapikan
- Style flash message jadi mendukung tipe (success/error)
- Semua form disesuaikan dengan dark mode
- Validasi form lebih ketat (client-side & server-side)
- Timeout session diperbaiki — panggil `session_start()` di bootstrap

**Fixed:**

- Bug: dropdown hilang setelah tutup modal validasi
- Bug: option baru tidak bisa diklik setelah quick-add
- Bug: option baru tidak ter-filter setelah quick-add
- Bug: session tidak tersimpan di `postDuplicate()`
- Bug: FK constraint error saat duplikat dengan `createdBy` invalid
- Bug: `flash_message` selalu hijau meski error
- Bug: `theme-toggle-mobile` null di halaman tertentu
- Bug: modal tidak tertutup setelah duplikat sukses

**Security:**

- Password hashing dengan `PASSWORD_BCRYPT`
- Validasi MIME type untuk upload avatar
- Batas ukuran file upload 2MB
- Session guard di middleware — handle null session
- Clear cookie saat session invalid

---

## Cara Release

### Prinsip Semantic Versioning

| Versi | Kapan | Contoh |
|---|---|---|
| **MAJOR** (`v2.0.0`) | Breaking change | Hapus API, ubah schema DB |
| **MINOR** (`v1.1.0`) | Fitur baru backward-compatible | Tambah fitur export CSV |
| **PATCH** (`v1.0.1`) | Bug fix | Fix typo, fix validation |

### Checklist Sebelum Release

- [ ] Semua test hijau (`vendor/bin/phpunit tests`)
- [ ] Manual test fitur utama di browser
- [ ] Update **Changelog** di README ini
- [ ] Update versi di `composer.json`
- [ ] Backup database (`mysqldump`)
- [ ] Cek `error.log` — tidak ada error
- [ ] Matikan `display_errors` di production
- [ ] Commit & push semua perubahan
- [ ] Buat tag & GitHub Release

### Langkah Release

**1. Commit semua perubahan**
```bash
git status
git add .
git commit -m "Release v1.0.0 — fitur duplikat, itemNo manual, quick-add"
```

**2. Buat tag beranotasi**
```bash
git tag -a v1.0.0 -m "Release v1.0.0

Fitur baru:
- Duplikat laporan + item giat
- Nomor giat manual
- Quick-add opsi
- Validasi hapus opsi
- Dark mode lengkap
- Layout sidebar

Test: 139 tests, 330 assertions"
```

**3. Push commit & tag**
```bash
git push origin master
git push origin v1.0.0

# Atau push semua tag sekaligus
git push --tags
```

**4. Buat GitHub Release**

Lewat web interface:
1. Buka repository di GitHub
2. Klik **Releases** → **Draft a new release**
3. **Choose a tag** → pilih `v1.0.0`
4. **Release title**: `v1.0.0 — Fitur Duplikat & Manual Item Number`
5. **Describe** — copy bagian `[1.0.0]` dari Changelog di atas
6. Upload artifact (opsional): schema DB, user guide
7. Klik **Publish release**

Lewat CLI:
```bash
gh release create v1.0.0 --title "v1.0.0" --notes "Release v1.0.0"
```

### Cek Tag

```bash
# Lihat semua tag
git tag -l

# Lihat detail tag
git show v1.0.0

# Checkout ke tag tertentu
git checkout v1.0.0

# Hapus tag (kalau salah)
git tag -d v1.0.0
git push origin --delete v1.0.0
```

### Alur Standar Industri

```
Commit → Tag (git) → Push tag → GitHub Release → Artifact upload
```

---

## Lisensi

MIT License

Copyright (c) 2026 Teknik Informatika UNIROW Tuban

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

---

## Author

**Teknik Informatika UNIROW Tuban**
- Polresta Tuban
- Email: hello@sitik-polresta-tuban.id

---

## Ucapan Terima Kasih

- Polresta Tuban — atas dukungan & feedback
- Tailwind CSS — framework CSS
- Lucide — icon set
- PHPUnit — testing framework