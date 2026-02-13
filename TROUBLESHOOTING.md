# 🔧 Troubleshooting Guide

## Masalah 1: "Halaman ini tidak berfungsi" - HTTP ERROR 502

Error 502 (Bad Gateway) di GitHub Codespaces biasanya disebabkan oleh salah satu dari hal berikut:

### ✅ Solusi 1: Restart Server (PALING UMUM)

Server mungkin crash atau port conflict. Jalankan commands ini:

```bash
# 1. Kill semua process yang menggunakan port 8000
fuser -k 8000/tcp
pkill -9 -f "php artisan serve"

# 2. Wait 2 detik
sleep 2

# 3. Start server lagi
php artisan serve --host=0.0.0.0 --port=8000
```

### ✅ Solusi 2: Clear Cache & Restart

```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Restart server
php artisan serve --host=0.0.0.0 --port=8000
```

### ✅ Solusi 3: Tunggu Port Forwarding Ready

Saat baru start server di GitHub Codespaces:

1. **Tunggu 5-10 detik** setelah server running
2. GitHub Codespaces perlu waktu untuk detect dan forward port
3. Lihat notifikasi "A service is available on port 8000" 
4. Klik **"Open in Browser"** dari notifikasi tersebut

### ✅ Solusi 4: Gunakan URL yang Benar

GitHub Codespaces memberikan unique URL untuk setiap port:

1. Buka **PORTS** tab di VS Code (biasanya di panel bawah)
2. Cari port **8000**

---

## Masalah 2: Link/URL redirect ke http://localhost:8000 (CODESPACES)

**Gejala:** Ketika klik tombol (Login, Register, dll), URL berubah ke `http://localhost:8000/...` padahal seharusnya tetap di URL Codespaces.

### ✅ Solusi: Update APP_URL

**Otomatis (Recommended):**
```bash
./update-codespace-url.sh
```

**Manual:**
1. Buka file `.env`
2. Update baris `APP_URL` dengan URL Codespaces Anda:
   ```
   APP_URL=https://XXXXX-8000.app.github.dev
   ```
   (Ganti XXXXX dengan codespace name Anda, lihat di PORTS tab)
3. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
4. Restart server:
   ```bash
   fuser -k 8000/tcp
   php artisan serve --host=0.0.0.0 --port=8000
   ```

**Atau gunakan fix-server.sh (sudah otomatis update URL):**
```bash
./fix-server.sh
```

### Penjelasan Teknis

Laravel menggunakan `APP_URL` dari `.env` untuk generate semua URL (route, asset, dll). Di GitHub Codespaces, aplikasi berjalan di belakang proxy dengan URL forwarded. Solusi yang sudah diimplementasikan:

1. **TrustProxies middleware** - Trust semua proxy headers
2. **Force HTTPS scheme** - Detect proxy dan force HTTPS
3. **APP_URL update** - Set URL sesuai environment

---
3. Klik **globe icon** atau klik kanan → **Open in Browser**
4. URL akan berbentuk: `https://XXXX-8000.app.github.dev`

### ✅ Solusi 5: Set Port Visibility ke Public

Di GitHub Codespaces:

1. Buka **PORTS** tab
2. Klik kanan pada port **8000**
3. Pilih **Port Visibility** → **Public**
4. Refresh browser

### ✅ Solusi 6: Gunakan Port Berbeda

Jika port 8000 bermasalah, coba port lain:

```bash
# Stop server di port 8000
fuser -k 8000/tcp

# Start di port lain, misal 3000
php artisan serve --host=0.0.0.0 --port=3000
```

### ✅ Solusi 7: Check Server Logs

Lihat apa yang terjadi di server:

```bash
# Lihat logs real-time
tail -f storage/logs/laravel.log

# Atau jika ada nohup.out
tail -f nohup.out

# Atau server.log jika menggunakan script
tail -f server.log
```

### ✅ Solusi 8: Restart Codespace

Jika semua solusi gagal:

1. Save semua file
2. Close codespace
3. Reopen codespace
4. Jalankan server lagi

---

## 🎯 Quick Fix Script

Simpan sebagai `fix-server.sh`:

```bash
#!/bin/bash

echo "🔧 Fixing server issues..."
echo ""

# Kill existing processes
echo "1. Killing existing server processes..."
fuser -k 8000/tcp 2>/dev/null
pkill -9 -f "php artisan serve" 2>/dev/null
sleep 2

# Clear cache
echo "2. Clearing cache..."
php artisan config:clear --quiet
php artisan cache:clear --quiet
php artisan view:clear --quiet

# Check database
echo "3. Checking database connection..."
php artisan tinker --execute="echo \App\Models\User::count() . ' users in database';"

# Start server
echo "4. Starting server on port 8000..."
echo ""
php artisan serve --host=0.0.0.0 --port=8000
```

Cara pakai:
```bash
chmod +x fix-server.sh
./fix-server.sh
```

---

## 📊 Diagnostic Commands

Untuk debugging:

```bash
# Check apakah port 8000 digunakan
lsof -i :8000

# Check PHP processes
ps aux | grep php

# Check server status
curl -I http://localhost:8000

# Test database connection
php artisan tinker --execute="echo \App\Models\User::count();"

# List all routes
php artisan route:list

# Check Laravel environment
php artisan env
```

---

## 🌐 Accessing in GitHub Codespaces

### Method 1: Auto Port Forwarding
Server akan otomatis terdeteksi dan GitHub akan memberikan URL publik

### Method 2: Manual dari PORTS Tab
1. Tekan `Ctrl + ~` untuk buka terminal panel
2. Klik tab **PORTS**
3. Cari port 8000
4. Klik **globe icon** untuk buka di browser

### Method 3: Dari Command Palette
1. Tekan `Ctrl + Shift + P`
2. Ketik "Forward a Port"
3. Masukkan `8000`
4. Klik open di browser

---

## ⚠️ Common Mistakes

1. **❌ Lupa tunggu server fully start**
   - ✅ Tunggu sampai muncul "Server running on..."

2. **❌ Port conflict (port sudah digunakan)**
   - ✅ Kill process dengan `fuser -k 8000/tcp`

3. **❌ Akses http:// di codespaces (harus https://)**
   - ✅ Gunakan URL dari PORTS tab (auto https)

4. **❌ Cache issue setelah edit code**
   - ✅ Clear cache: `php artisan cache:clear`

5. **❌ Permission error di storage/**
   - ✅ Fix: `chmod -R 775 storage bootstrap/cache`

---

## ✅ Verification Checklist

Setelah start server, verify dengan:

- [ ] Terminal menampilkan "Server running on [http://0.0.0.0:8000]"
- [ ] Port 8000 muncul di PORTS tab
- [ ] Status di PORTS tab: Running (bukan crashed)
- [ ] `curl http://localhost:8000` returns HTML (dari dalam codespace)
- [ ] Browser bisa akses via forwarded URL
- [ ] Tidak ada error di `storage/logs/laravel.log`

---

## 🆘 Still Not Working?

Jika masih error:

1. **Cek Error Logs:**
   ```bash
   cat storage/logs/laravel.log
   ```

2. **Restart Everything:**
   ```bash
   php artisan config:clear
   composer dump-autoload
   php artisan migrate:fresh --seed
   php artisan serve --host=0.0.0.0 --port=8000
   ```

3. **Test dengan curl dari dalam codespace:**
   ```bash
   curl -v http://localhost:8000
   ```

4. **Contact atau buat issue** jika masih bermasalah

---

## 📝 Pro Tips

- **Gunakan `./dev.sh`** untuk menu interaktif
- **Monitor logs** dengan `tail -f storage/logs/laravel.log` di terminal terpisah
- **Set port visibility ke Public** untuk mudah share link
- **Bookmark forwarded URL** dari PORTS tab agar tidak perlu cari lagi

Happy coding! 🚀
