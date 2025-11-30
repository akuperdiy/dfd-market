# Sistem Informasi Manajemen Supermarket

Aplikasi web berbasis Laravel 10 untuk mengelola operasional supermarket secara digital. Sistem ini dirancang untuk memudahkan pengelolaan produk, penjualan, stok, dan laporan keuangan dalam satu platform terintegrasi.

## Tentang Aplikasi

Aplikasi ini dibuat untuk membantu mengelola berbagai aspek operasional supermarket, mulai dari manajemen produk, proses pembelian dari supplier, pengelolaan stok, hingga sistem kasir untuk transaksi penjualan. Dengan sistem role-based access control, setiap pengguna memiliki akses sesuai dengan tugas dan tanggung jawabnya.

## Fitur Utama

### 🛍️ Master Data
Kelola data produk dan supplier dengan mudah. Fitur ini memungkinkan Anda menambahkan, mengedit, dan menghapus produk beserta informasi lengkapnya seperti SKU, barcode, harga beli, harga jual, dan stok. Selain itu, Anda juga bisa mengelola data supplier yang bekerja sama dengan supermarket.

### 📦 Purchase Order (PO)
Sistem pembelian yang terstruktur untuk memesan produk dari supplier. Buat purchase order, terima barang yang datang, dan sistem akan otomatis mengupdate stok produk di gudang.

### 📊 Stock Management
Pantau stok produk secara real-time. Sistem ini mencatat setiap pergerakan stok, baik masuk maupun keluar, sehingga Anda selalu tahu kondisi stok terkini. Ada juga fitur stock adjustment untuk koreksi stok jika diperlukan.

### 💰 Point of Sale (POS)
Sistem kasir yang praktis dan cepat. Scan barcode produk langsung dari barcode scanner, tambahkan ke keranjang, hitung total, dan cetak invoice. Sistem ini juga mendukung diskon dan berbagai metode pembayaran (cash, debit, credit).



### 📈 Laporan
Lihat laporan penjualan dan stok untuk analisis bisnis. Laporan penjualan menampilkan ringkasan transaksi dalam periode tertentu, sedangkan laporan stok memberikan informasi tentang kondisi stok semua produk.

### 💾 Backup Database
Fitur backup otomatis untuk melindungi data penting. Buat backup database kapan saja dan download file backup untuk disimpan sebagai cadangan.

## Persyaratan Sistem

Sebelum menginstall aplikasi ini, pastikan komputer Anda sudah terinstall:

- **PHP** versi 8.1 atau lebih tinggi
- **Composer** untuk mengelola dependencies PHP
- **MySQL** atau **MariaDB** sebagai database
- **Node.js** dan **NPM** untuk mengelola assets frontend

## Cara Install

### 1. Clone atau Download Project
Jika menggunakan Git, clone repository ini:
```bash
git clone https://github.com/YOUR_USERNAME/REPO_NAME.git
cd supermarket-system
```

Atau download dan extract file ZIP ke folder lokal Anda.

### 2. Install Dependencies
Jalankan perintah berikut untuk menginstall semua package yang dibutuhkan:

```bash
composer install
npm install
```

### 3. Setup Environment
Copy file `.env.example` menjadi `.env`:
```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

Kemudian generate application key:
```bash
php artisan key:generate
```

### 4. Konfigurasi Database
Buka file `.env` dan sesuaikan konfigurasi database Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supermarket_db
DB_USERNAME=root
DB_PASSWORD=
```

Jangan lupa buat database `supermarket_db` di MySQL terlebih dahulu.

### 5. Jalankan Migration dan Seeder
Setup database dengan menjalankan:
```bash
php artisan migrate:fresh --seed
```

Perintah ini akan membuat semua tabel yang diperlukan dan mengisi data awal seperti role pengguna dan akun default.

### 6. Compile Assets
Compile file CSS dan JavaScript:
```bash
npm run dev
```

Atau untuk production:
```bash
npm run build
```

### 7. Jalankan Server
Start development server:
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Akun Login Default

Setelah menjalankan seeder, Anda bisa login menggunakan akun berikut:

### 👤 Administrator
- **Username**: `Adminonly`
- **Password**: `khususadmin`
- **Akses**: Full access ke semua fitur

### 💵 Kasir
Tersedia 3 akun kasir dengan password yang sama:
- **Username**: `Ferdi` | **Password**: `kasir123`
- **Username**: `Dudung` | **Password**: `kasir123`
- **Username**: `Farrel` | **Password**: `kasir123`
- **Akses**: POS, penjualan

### 📦 Staff Gudang
- **Username**: `Gudang1`
- **Password**: `gudang123`
- **Akses**: Purchase order, stock management, dan stock adjustment

### 💼 Manager
- **Username**: `Manager1`
- **Password**: `manager123`
- **Akses**: Laporan penjualan dan stok

- **sales** - Data transaksi penjualan
- **sale_items** - Detail item yang terjual dalam setiap transaksi
- **stock_movements** - History semua pergerakan stok (masuk/keluar)

- **backups** - Catatan file backup yang pernah dibuat

## API Endpoints

Aplikasi menyediakan beberapa API endpoint untuk integrasi dengan sistem lain:

### Mencari Produk berdasarkan Barcode
```
GET /api/products?barcode={barcode}
```

Response:
```json
{
  "id": 1,
  "name": "Beras Premium 5kg",
  "sku": "PRD001",
  "sell_price": 55000,
  "stock": 50
}
```

### Membuat Transaksi Penjualan
```
POST /api/sales
```

Request Body:
```json
{
  "items": [
    {
      "product_id": 1,
      "qty": 2,
      "price": 55000
    }
  ],
  "customer_name": "John Doe",
  "discount": 0,
  "payment_method": "cash"
}
```

Response:
```json
{
  "success": true,
  "sale_id": 1,
  "invoice_no": "INV-20241113-0001",
  "total": 110000,
  "items": [...]
}
```

**Catatan**: Untuk menggunakan API, Anda perlu login terlebih dahulu dan menggunakan session cookie atau token authentication.

## Hak Akses per Role

### Admin
Memiliki akses penuh ke semua fitur:
- Master data (Produk & Supplier)
- Laporan penjualan dan stok
- Backup database
- Semua fitur kasir dan gudang
- Dashboard monitoring

### Kasir
Akses terbatas hanya untuk transaksi penjualan:
- Point of Sale (POS)
- Tidak memiliki akses ke Dashboard, Gudang, atau Laporan

### Gudang
Akses terbatas hanya untuk pengelolaan stok:
- Purchase Order
- Stock management
- Stock adjustment
- Menerima barang dari supplier
- Tidak memiliki akses ke Dashboard, POS, atau Laporan

### Manager
Akses terbatas hanya untuk monitoring:
- Dashboard utama
- Laporan penjualan
- Laporan stok
- Tidak memiliki akses ke operasional (POS/Gudang)

## Tips Penggunaan

### Keyboard Shortcuts di POS
Untuk mempercepat proses di halaman POS, gunakan shortcut berikut:
- **F1** - Fokus ke input barcode
- **F2** - Proses pembayaran
- **F3** - Bersihkan keranjang
- **Esc** - Hapus input barcode
- **Ctrl/Cmd + Enter** - Proses pembayaran
- **Enter** - Cari produk (saat di input barcode)

### Barcode Scanner
Barcode scanner bekerja seperti keyboard. Setelah scan, tekan Enter atau biarkan scanner mengirimkan Enter secara otomatis untuk menambahkan produk ke keranjang.

### Backup Rutin
Sangat disarankan untuk membuat backup database secara rutin, terutama sebelum melakukan update atau perubahan besar pada sistem.

## Troubleshooting

### Error "Call to a member function parameters() on null"
Error ini sudah diperbaiki dengan menghapus SubstituteBindings dari global middleware. Jika masih muncul, pastikan semua route sudah terdaftar dengan benar.

### Database Connection Error
Pastikan:
- MySQL service sedang berjalan
- Konfigurasi database di `.env` sudah benar
- Database sudah dibuat
- Username dan password MySQL sudah sesuai

### Backup Gagal
Pastikan `mysqldump` sudah terinstall dan tersedia di PATH sistem. Untuk Windows, biasanya sudah termasuk dalam instalasi MySQL.

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, JavaScript (Vanilla)
- **Database**: MySQL/MariaDB
- **Icons**: Bootstrap Icons

## Lisensi

Project ini dibuat untuk keperluan tugas kuliah Sistem Informasi Manajemen.

## Kontributor

Dikembangkan sebagai bagian dari pembelajaran dan implementasi sistem informasi manajemen untuk operasional supermarket.

---

**Selamat menggunakan!** Jika ada pertanyaan atau menemukan bug, silakan buat issue di repository ini.
