# Nasi Rames V2

Nasi Rames V2 adalah aplikasi web e-commerce dan kasir (Point of Sale) untuk pemesanan makanan, khususnya Nasi Rames. Aplikasi ini dibangun menggunakan framework **Laravel**. 

Aplikasi ini melayani dua jenis pengguna utama:
1. **Pelanggan (Customer):** Dapat melihat menu, memasukkan pesanan ke keranjang (cart), melakukan checkout, dan mengelola profil.
2. **Administrator:** Dapat mengelola data menu (termasuk stok), melihat dan memproses pesanan, mengelola pengguna (users), serta memiliki fitur Kasir (POS) untuk melayani pesanan secara langsung di tempat.

## 📂 Struktur Proyek

Proyek ini menggunakan arsitektur MVC (Model-View-Controller) bawaan Laravel. Berikut adalah rincian struktur utama dalam proyek ini:

### 1. Models (`app/Models`)
Berisi representasi data dari database. Terdapat 4 model utama:
- `User.php`: Menangani data pengguna (pelanggan dan admin).
- `Menu.php`: Menangani data produk/makanan yang dijual, beserta harga dan informasi stok.
- `Order.php`: Menangani data transaksi pesanan secara keseluruhan (termasuk total harga, status pesanan, dan lokasi/meja).
- `OrderItem.php`: Menangani data detail item dari setiap pesanan (relasi antara pesanan dan menu).

### 2. Controllers (`app/Http/Controllers`)
Menangani logika bisnis aplikasi:
- `HomeController.php`: Mengelola halaman utama, tampilan menu, keranjang belanja (cart), dan proses checkout pelanggan.
- `AuthController.php`: Menangani proses autentikasi seperti Login, Register, dan Logout.
- `AdminController.php`: Menangani seluruh fungsi halaman dashboard admin, termasuk CRUD menu, manajemen pengguna, pemrosesan status pesanan, serta fitur Kasir.
- Controller lainnya: Terdapat juga `MenuController`, `OrderController`, dan `OrderItemController` (kemungkinan digunakan untuk API atau fungsi spesifik lainnya).

### 3. Routes (`routes/web.php`)
Mengatur jalur URL web. Dibagi menjadi beberapa kelompok:
- **Public Routes:** `/` (Beranda), `/menu` (Lihat Menu), `/login`, `/register`.
- **Customer Routes (Auth):** `/profile`, `/cart`, `/order`, `/checkout` (proses pembayaran).
- **Admin Routes (Auth + Admin Middleware):** 
  - `/admin` (Dashboard)
  - `/admin/menus` (Manajemen Produk/Stok)
  - `/admin/users` (Manajemen Pengguna)
  - `/admin/orders` (Manajemen Pesanan)
  - `/admin/kasir` (Sistem Kasir/Point of Sale)

### 4. Views (`resources/views`)
Tampilan antarmuka (UI) menggunakan sistem templating Blade:
- `/`: Tampilan umum seperti `welcome.blade.php`, `menu.blade.php`, `cart.blade.php`, `profile.blade.php`.
- `/admin`: Berisi semua antarmuka khusus halaman dashboard Administrator.
- `/auth`: Tampilan form login dan register.
- `/layouts` & `/components`: Komponen layout yang digunakan ulang di berbagai halaman.

### 5. Database & Migrations (`database/migrations`)
Struktur tabel database yang telah dibuat:
- `users`: Data pengguna aplikasi.
- `menus`: Data menu dengan tambahan atribut `stock`.
- `orders`: Data pesanan dengan tambahan atribut `location`.
- `order_items`: Detail menu yang dipesan pada setiap pesanan.

## 🚀 Cara Menjalankan Proyek (Local Development)

1. Pastikan Anda telah menginstal **PHP**, **Composer**, dan **Node.js**.
2. Clone atau ekstrak proyek ini.
3. Jalankan perintah instalasi dependency:
   ```bash
   composer install
   npm install
   ```
4. Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda (misalnya menggunakan SQLite atau MySQL):
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi dan seeder untuk membangun database:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Catatan: Proyek ini juga memiliki route sementara `/seed-db` untuk melakukan optimasi dan seeding database melalui browser).*
7. Compile aset frontend (Tailwind/Vite):
   ```bash
   npm run dev
   ```
8. Jalankan local server Laravel:
   ```bash
   php artisan serve
   ```
9. Aplikasi dapat diakses melalui `http://localhost:8000`.

---
*README ini di-generate berdasarkan struktur aplikasi untuk memberikan pemahaman mengenai alur kerja dan arsitektur website Nasi Rames V2.*
