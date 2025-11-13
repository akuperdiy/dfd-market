# Sistem Informasi Supermarket

Aplikasi Sistem Informasi Manajemen untuk Supermarket berbasis Laravel 10.

## Fitur

- **Master Data**: Manajemen Produk dan Supplier
- **Purchase Order**: Pembuatan dan penerimaan PO dari supplier
- **Stock Management**: Tracking stock, stock adjustment, dan batch tracking
- **Point of Sale (POS)**: Sistem kasir dengan scan barcode
- **Retur**: Proses retur penjualan
- **Laporan**: Laporan penjualan dan stock
- **Backup**: Backup database
- **Role-based Access Control**: Admin, Kasir, Gudang, Manager

## Requirements

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM

## Instalasi

1. Clone atau extract project ke folder lokal
2. Masuk ke folder project:
   ```bash
   cd supermarket-system
   ```

3. Install dependencies:
   ```bash
   composer install
   npm install
   ```

4. Copy file `.env.example` menjadi `.env`:
   ```bash
   copy .env.example .env
   ```
   (Windows) atau
   ```bash
   cp .env.example .env
   ```
   (Linux/Mac)

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Konfigurasi database di file `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=supermarket_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Buat database `supermarket_db` di MySQL

8. Jalankan migrations dan seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

9. Compile assets:
   ```bash
   npm run dev
   ```

10. Jalankan server:
    ```bash
    php artisan serve
    ```

11. Buka browser: `http://localhost:8000`

## Login Default

- **Admin**: 
  - Email: `admin@example.com`
  - Password: `password123`

- **Kasir**: 
  - Email: `kasir@example.com`
  - Password: `password123`

- **Gudang**: 
  - Email: `gudang@example.com`
  - Password: `password123`

## Struktur Database

### Tabel Utama:
- `roles` - Role pengguna
- `users` - Data pengguna
- `suppliers` - Data supplier
- `products` - Data produk
- `product_batches` - Batch produk (untuk tracking expiry)
- `purchase_orders` - Purchase order
- `purchase_items` - Item dalam PO
- `sales` - Data penjualan
- `sale_items` - Item dalam penjualan
- `stock_movements` - History pergerakan stock
- `returns` - Data retur
- `return_items` - Item retur
- `backups` - Data backup

## API Endpoints

### GET /api/products?barcode={barcode}
Mencari produk berdasarkan barcode.

**Response:**
```json
{
  "id": 1,
  "name": "Beras Premium 5kg",
  "sku": "PRD001",
  "sell_price": 55000,
  "stock": 50
}
```

### POST /api/sales
Membuat transaksi penjualan.

**Request Body:**
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

**Response:**
```json
{
  "success": true,
  "sale_id": 1,
  "invoice_no": "INV-20241113-0001",
  "total": 110000
}
```

## Testing API dengan cURL

### Test Sales API:
```bash
curl -X POST http://localhost:8000/api/sales \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=YOUR_SESSION_COOKIE" \
  -d '{
    "items": [
      {
        "product_id": 1,
        "qty": 1,
        "price": 55000
      }
    ],
    "discount": 0,
    "payment_method": "cash"
  }'
```

**Catatan**: Untuk testing API, Anda perlu login terlebih dahulu dan menggunakan session cookie atau menggunakan Postman dengan authentication.

## File Penting

- **POS View**: `resources/views/sales/pos.blade.php`
- **Migration Utama**: `database/migrations/2024_01_01_000004_create_products_table.php`
- **Sales Controller**: `app/Http/Controllers/SalesController.php`

## Role dan Akses

- **Admin**: Akses penuh (master data, reports, backup)
- **Kasir**: POS, penjualan, retur
- **Gudang**: Purchase order, stock management, stock adjustment
- **Manager**: Laporan penjualan dan stock

## Catatan

- Pastikan MySQL service berjalan sebelum menjalankan migrations
- Untuk production, set `APP_DEBUG=false` di `.env`
- Backup database menggunakan mysqldump (pastikan tersedia di PATH)
- Barcode scanner bekerja sebagai keyboard input (Enter untuk scan)

## Lisensi

Project ini dibuat untuk keperluan tugas kuliah.

