# Perbaikan Masalah Database Connection

## Masalah:
`Access denied for user 'root'@'localhost' (using password: NO)`

## Solusi:

### Opsi 1: Buat Database via phpMyAdmin (Paling Mudah)

1. Buka browser, kunjungi: `http://localhost/phpmyadmin`
2. Login dengan:
   - Username: `root`
   - Password: (kosongkan jika tidak ada password, atau masukkan password XAMPP Anda)
3. Klik tab **"SQL"** di bagian atas
4. Jalankan query ini:
   ```sql
   CREATE DATABASE IF NOT EXISTS supermarket_db;
   ```
5. Klik **"Go"** atau tekan Enter
6. Database akan dibuat

### Opsi 2: Set Password di .env (jika MySQL root punya password)

Edit file `.env` dan set password:
```
DB_PASSWORD=password_anda_disini
```

### Opsi 3: Reset MySQL Root Password (jika lupa)

Jika Anda lupa password MySQL root:
1. Stop MySQL di XAMPP Control Panel
2. Edit file: `C:\xampp\mysql\bin\my.ini`
3. Tambahkan baris ini di bawah `[mysqld]`:
   ```
   skip-grant-tables
   ```
4. Start MySQL lagi
5. Login tanpa password
6. Set password baru:
   ```sql
   ALTER USER 'root'@'localhost' IDENTIFIED BY '';
   ```
7. Hapus baris `skip-grant-tables` dari my.ini
8. Restart MySQL

### Setelah Database Dibuat:

Jalankan migrations:
```powershell
php artisan migrate:fresh --seed
```

