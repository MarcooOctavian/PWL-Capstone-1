<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
# Project Capstone 1

Project capstone ini bertujuan untuk membangun sebuah platform manajemen event dan penjualan tiket berbasis web yang memungkinkan:
- Admin membuat dan mengelola event
- Organizer memantau penjualan tiket
- Pengguna melakukan registrasi, login, dan pembelian tiket
- Sistem menghasilkan e-ticket dengan QR Code
- Dashboard analitik penjualan secara real-time

##### **Fitur yang wajib diimplementasikan**

###### Authentication & Authorization

- Register & Login
- Role: Admin, Organizer, User
- Password hashing
- Middleware route

###### Manajemen Event

- CRUD Event
- Upload banner
- Kategori event
- Jadwal & lokasi
- Kuota tiket

###### Sistem Ticketing

- Pemilihan jenis tiket (VIP, Regular, dll.)
- Manajemen stok otomatis
- Generate e-ticket (QR Code)
- Validasi tiket (scan simulation)
- Queue & Waiting List
- Pengiriman ticket dan QR ke email

###### Dashboard & Reporting

- Statistik penjualan
- Grafik transaksi
- Total revenue
- Event performance analytics
- Export report ke Excel atau PDF

###### Payment Integration

- Simulasi pembayaran
- Status transaksi (pending, paid, failed)

---

# Eventify — Platform Manajemen & Penjualan Tiket Event

Aplikasi web berbasis **Laravel 12** untuk manajemen event dan penjualan tiket online. Pengunjung dapat menjelajahi event, membeli tiket, dan menerima **e-ticket ber-QR code**, sementara admin dan organizer mengelola event, tipe tiket, transaksi, hingga laporan penjualan melalui panel admin.

## Peran Pengguna (Roles)

Peran disimpan pada kolom `role` di tabel `users`:

| Role | Nilai | Akses |
|------|-------|-------|
| **Admin** | `1` | Akses penuh: manajemen user, kategori, lokasi, persetujuan organizer, dan seluruh panel admin. |
| **Organizer** | `2` | Mengelola event, jadwal, tipe tiket, dan waiting list. |
| **User** | `3` | Membeli tiket, melihat e-ticket, bergabung waiting list, dan mengajukan menjadi organizer. |

Otorisasi ditangani oleh `RoleMiddleware` dan `CheckUserStatus` middleware.

## Teknologi

- **PHP** ^8.2
- **Laravel Framework** ^12.0
- **Laravel Breeze** — scaffolding autentikasi
- **MySQL** — basis data utama
- **Blade + TailwindCSS 3 + Alpine.js** — frontend
- **Vite 7** — bundling aset
- **barryvdh/laravel-dompdf** — generate PDF
- **maatwebsite/excel** — import/export Excel
- **simplesoftwareio/simple-qrcode** — generate QR code tiket

## Struktur Domain

Model dan relasi inti aplikasi:

- **User**: memiliki banyak `Transaction` dan `OrganizerRequest` (soft deletes).
- **Event**: milik `User` (organizer), `Category`, `Location`; memiliki banyak `Schedule`, `TypeTicket`, dan `Ticket` (via `TypeTicket`).
- **TypeTicket**: tipe/harga tiket dalam sebuah event.
- **Transaction**: pembelian oleh user; menampung banyak `Ticket` dengan `payment_status`.
- **Ticket**: tiket individual dengan `qr_code` dan `status`.
- **WaitingList**: antrean pengguna ketika kuota habis.
- **OrganizerRequest**: pengajuan user menjadi organizer.

## Prasyarat

- PHP **8.2+** beserta ekstensi standar Laravel
- Composer
- Node.js & npm
- MySQL (atau MariaDB)

## Instalasi

```bash
# 1. Clone repository
git clone <repository-url>
cd PWL-Capstone-1

# 2. Install dependency PHP & JavaScript
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PWLCP1
DB_USERNAME=root
DB_PASSWORD=
```

Buat database (mis. `PWLCP1`) terlebih dahulu, lalu jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

> Alternatif cepat: `composer run setup` menjalankan install, key generate, migrate, dan build aset sekaligus.

## Menjalankan Aplikasi

**Cara cepat (semua proses sekaligus)** — server, queue, log, dan Vite:

```bash
composer run dev
```

**Cara manual (dua terminal):**

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite dev server
npm run dev
```

Aplikasi tersedia di `http://localhost:8000`.

## Akun Default (Seeder)

Setelah menjalankan seeder, tersedia akun berikut (password semua: `password`):

| Peran | Email | Password |
|-------|-------|----------|
| Admin | `admin@eventify.com` | `password` |
| Organizer | `budi@eventify.com` | `password` |
| Organizer | `sari@eventify.com` | `password` |
| User | `andi@mail.com` | `password` |

> Login admin/organizer melalui rute `/login-admin`.

## Rute Penting

| Rute | Deskripsi |
|------|-----------|
| `/` | Beranda publik & daftar event mendatang |
| `/events` | Katalog event publik |
| `/login-admin` | Login admin & organizer |
| `/register` | Registrasi pengguna |
| `/panel` | Dashboard admin |
| `/checkout` | Alur pembelian tiket |
| `/e-ticket/{id}` | Tampilan e-ticket dengan QR code |
| `/scan/{qr_code}` | Pemindaian & validasi tiket |
| `/export/excel`, `/export/pdf` | Ekspor laporan |

## Testing

```bash
composer run test
# atau
php artisan test
```

---

Dibangun dengan Laravel sebagai proyek Capstone Pemrograman Web Lanjut.
