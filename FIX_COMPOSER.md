# Perbaikan Masalah Composer Install

## Masalah yang Terjadi:
1. **Missing zip extension**: PHP tidak punya ekstensi zip yang aktif
2. **Process timeout**: Timeout saat clone dari GitHub

## Solusi:

### 1. Aktifkan PHP Zip Extension

1. Buka file `C:\xampp\php\php.ini` dengan text editor (Notepad++ atau VS Code)
2. Cari baris: `;extension=zip` (ada tanda `;` di depan)
3. Hapus tanda `;` menjadi: `extension=zip`
4. Simpan file
5. Restart XAMPP atau restart terminal

**Atau gunakan command ini untuk mengaktifkan otomatis:**
```powershell
(Get-Content C:\xampp\php\php.ini) -replace ';extension=zip', 'extension=zip' | Set-Content C:\xampp\php\php.ini
```

### 2. Verifikasi Zip Extension Aktif
```powershell
php -m | Select-String zip
```
Harus muncul: `zip`

### 3. Jalankan Composer Install Lagi

Setelah zip extension aktif, jalankan:
```powershell
cd "C:\Users\perdi\OneDrive\Documents\Sistem Informasi Manajemen\SIMS\supermarket-system"
composer install --prefer-dist
```

**Penjelasan:**
- `--prefer-dist` akan download file zip daripada clone dari git (lebih cepat)

### 4. Jika Masih Timeout

Tingkatkan timeout di composer:
```powershell
composer install --prefer-dist --timeout=600
```

Atau set di config global:
```powershell
composer config --global process-timeout 600
```

### 5. Alternatif: Install dengan Prefer Source (jika zip masih bermasalah)

```powershell
composer install --prefer-source --timeout=600
```

**Catatan**: Ini akan lebih lambat karena clone dari git, tapi tidak butuh zip extension.

