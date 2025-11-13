# Cara Memperbaiki Composer di Windows

## Masalah: PowerShell tidak mengenali command `composer`

### Solusi 1: Restart Terminal/PowerShell
Setelah install Composer, **tutup dan buka ulang PowerShell/CMD** agar perubahan PATH berlaku.

### Solusi 2: Cek Lokasi Composer
Composer biasanya terinstall di salah satu lokasi ini:
- `C:\ProgramData\ComposerSetup\bin\composer.bat`
- `C:\Users\[Username]\AppData\Local\Programs\ComposerSetup\bin\composer.bat`
- `C:\Program Files\ComposerSetup\bin\composer.bat`

### Solusi 3: Tambahkan ke PATH Manual

1. **Cari lokasi composer.bat**:
   ```powershell
   Get-ChildItem -Path C:\ -Filter composer.bat -Recurse -ErrorAction SilentlyContinue | Select-Object FullName
   ```

2. **Tambahkan ke PATH**:
   - Buka **System Properties** → **Environment Variables**
   - Edit **Path** di **User variables** atau **System variables**
   - Tambahkan path folder yang berisi `composer.bat` (biasanya folder `bin`)
   - Contoh: `C:\ProgramData\ComposerSetup\bin`

3. **Restart PowerShell** setelah menambah PATH

### Solusi 4: Install Ulang Composer dengan PATH

1. Download Composer Installer dari: https://getcomposer.org/download/
2. Jalankan installer
3. **Pastikan centang opsi "Add to PATH"** saat instalasi
4. Restart terminal setelah instalasi selesai

### Solusi 5: Gunakan Full Path Sementara

Jika sudah tahu lokasi composer.bat, gunakan full path:
```powershell
C:\ProgramData\ComposerSetup\bin\composer.bat install
```

### Verifikasi
Setelah salah satu solusi di atas, test dengan:
```powershell
composer --version
```

Jika berhasil, akan muncul versi Composer.

