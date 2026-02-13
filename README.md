# Sistem Perizinan Reklame Online

Sistem manajemen perizinan reklame online berbasis Laravel yang memungkinkan pengajuan izin reklame secara online dengan alur persetujuan hierarkis.

## Fitur Utama

### Alur Perizinan
1. **User (Pemohon)** mengajukan permohonan izin reklame dengan mengisi formulir dan mengunggah dokumen
2. **Operator** mereview permohonan (dengan sistem klaim untuk menghindari pengecekan duplikat)
3. **Kasi Perizinan** mereview permohonan yang sudah disetujui operator
4. **Kabid Penyelenggaraan** memberikan persetujuan final dan menerbitkan nomor izin
5. **Pemohon** dapat melacak status permohonan secara real-time

### Data Permohonan
- Nama/Badan/Organisasi
- Alamat
- Nomor Telepon
- Klasifikasi (Permanen/Non Permanen)
- Ukuran/Jumlah
- Narasi
- Lokasi GPS presisi (menggunakan peta interaktif Leaflet.js)

### Dokumen yang Diunggah
- Foto Kopi KTP Berwarna
- Foto Copy NPWP Berwarna
- Foto Copy Akte Pendirian
- Foto Copy Retribusi Pajak Reklame
- Data Isian Pemohon
- Surat Pernyataan Pertanggung Jawaban Konstruksi
- Foto Kondisi dan Gambar Tampilan Visualisasi Reklame
- Gambar Konstruksi Bidangan
- Surat Permohonan Izin
- Surat Kuasa (Opsional)

## Instalasi Lokal

### Persyaratan
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### Langkah Instalasi

#### Opsi 1: Quick Setup (Recommended)
```bash
git clone https://github.com/alhamdy1/magang.git
cd magang
composer setup  # Install semua dan setup otomatis
php artisan serve
```

#### Opsi 2: Manual Setup
1. Clone repository
```bash
git clone https://github.com/alhamdy1/magang.git
cd magang
```

2. Install dependencies
```bash
composer install
npm install
```

3. Copy file environment
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Buat database dan jalankan migrasi
```bash
touch database/database.sqlite
php artisan migrate --seed
```

6. Build assets frontend
```bash
npm run build
```

7. Set storage permissions
```bash
chmod -R 775 storage bootstrap/cache
```

8. Jalankan server
```bash
php artisan serve
```

9. Akses aplikasi di `http://localhost:8000`

### 🐙 GitHub Codespaces

Untuk development di cloud tanpa install apapun:

1. Buka repository di GitHub
2. Klik **Code** → **Codespaces** → **Create codespace on main**
3. Tunggu codespace ready (auto-install dependencies)
4. Di terminal, jalankan:
   ```bash
   ./fix-server.sh
   # atau
   php artisan serve --host=0.0.0.0 --port=8000
   ```
5. Tunggu notifikasi port forwarding, klik **"Open in Browser"**
6. Atau buka **PORTS** tab dan klik icon globe pada port 8000

**Troubleshooting di Codespaces:**
- Jika dapat HTTP 502: Tunggu 10 detik, refresh browser
- Jika server crash: Jalankan `./fix-server.sh`
- Lihat [TROUBLESHOOTING.md](TROUBLESHOOTING.md) untuk detail

### 📚 Dokumentasi Testing

- **[TEST_GUIDE.md](TEST_GUIDE.md)** - Panduan testing lengkap dengan skenario
- **[QUICK_START.txt](QUICK_START.txt)** - Quick reference card
- **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Solusi masalah umum
- **[dev.sh](dev.sh)** - Script helper untuk development

---

## 🚀 Opsi Deploy untuk Testing (Free Tier)

> ⚠️ **Catatan Penting tentang Netlify**: Netlify adalah platform untuk static sites dan serverless functions yang **tidak mendukung PHP/Laravel** secara native. Untuk aplikasi Laravel full-stack seperti ini, gunakan salah satu platform berikut:

### Pilihan Platform (Rekomendasi)

| Platform | Free Tier | Kelebihan |
|----------|-----------|-----------|
| **Render.com** | ✅ 750 jam/bulan | Mudah setup, GitHub integration, cocok untuk testing |
| **Fly.io** | ✅ 3 VM gratis | Performa bagus, global deployment |
| **Railway** | ⚠️ $5 kredit | Tidak ada free tier lagi, berbayar |

---

## 🌐 Deploy ke Render.com (Recommended untuk Testing)

Render.com adalah platform cloud yang menyediakan free tier untuk testing Laravel applications.

### Cara 1: Deploy Otomatis dengan Blueprint

1. **Fork/Push Repository ke GitHub**
   - Pastikan repository sudah ada di GitHub Anda

2. **Buat Akun Render.com**
   - Kunjungi [render.com](https://render.com)
   - Sign up menggunakan akun GitHub

3. **Deploy dengan Blueprint**
   - Klik "New" → "Blueprint"
   - Connect repository GitHub Anda
   - Render akan membaca file `render.yaml` dan setup otomatis

4. **Generate APP_KEY**
   - Jalankan lokal: `php artisan key:generate --show`
   - Tambahkan sebagai Environment Variable di Render Dashboard:
     ```
     APP_KEY=base64:xxxxx
     ```

5. **Deploy**
   - Render akan otomatis build dan deploy
   - Setelah selesai, akses URL yang diberikan

### Cara 2: Deploy Manual

1. **Buat Web Service Baru**
   - Klik "New" → "Web Service"
   - Connect ke repository GitHub
   - Runtime: Docker
   - Region: Singapore (terdekat)

2. **Set Environment Variables**
   
   Di Render Dashboard → Environment, tambahkan:
   ```
   APP_NAME=Sistem Perizinan Reklame
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:xxxxx
   APP_LOCALE=id
   APP_TIMEZONE=Asia/Jakarta
   
   DB_CONNECTION=sqlite
   
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   
   LOG_CHANNEL=stack
   LOG_LEVEL=error
   PORT=8080
   ```

3. **Deploy**
   - Klik "Create Web Service"
   - Tunggu build selesai

### File Konfigurasi Render.com

Repository ini sudah dilengkapi dengan:
- `render.yaml` - Blueprint untuk auto-deploy
- `.env.render.example` - Contoh variabel environment
- `render-start.sh` - Script startup khusus Render
- `Dockerfile` - Konfigurasi container Docker

### Catatan Penting Render.com Free Tier

- **750 jam gratis per bulan** - Cukup untuk 1 instance 24/7
- **Auto-sleep setelah 15 menit idle** - Aplikasi akan sleep jika tidak diakses
- **Cold start ~30 detik** - Pertama kali akses setelah sleep agak lambat
- **Database SQLite** - Data akan reset saat redeploy
- **Untuk data persisten** - Gunakan Render PostgreSQL (berbayar)

---

## ✈️ Deploy ke Fly.io (Alternatif)

Fly.io adalah platform yang menawarkan 3 VM gratis dengan performa bagus.

### Langkah Deploy

1. **Install Fly CLI**
   ```bash
   # macOS
   brew install flyctl
   
   # Windows
   powershell -Command "iwr https://fly.io/install.ps1 -useb | iex"
   
   # Linux
   curl -L https://fly.io/install.sh | sh
   ```

2. **Login ke Fly.io**
   ```bash
   fly auth login
   ```

3. **Deploy Aplikasi**
   ```bash
   # Di folder repository
   fly launch --copy-config
   
   # Set APP_KEY
   fly secrets set APP_KEY=base64:xxxxx
   
   # Deploy
   fly deploy
   ```

4. **Akses Aplikasi**
   ```bash
   fly open
   ```

### File Konfigurasi Fly.io

Repository ini sudah dilengkapi dengan:
- `fly.toml` - Konfigurasi Fly.io
- `.env.fly.example` - Contoh variabel environment

### Catatan Penting Fly.io Free Tier

- **3 VM gratis** - Shared CPU, 256MB RAM
- **Auto-scale ke 0** - Hemat resources saat tidak digunakan
- **Global deployment** - Deploy ke region terdekat
- **Database SQLite** - Data akan reset saat redeploy

---

## 🚂 Deploy ke Railway (Berbayar)

> ⚠️ **Catatan**: Railway sudah tidak menyediakan free tier. Minimum $5/bulan.

Railway masih bisa digunakan jika Anda bersedia membayar. Berikut langkah-langkahnya:

### Cara Deploy

1. **Buat Akun Railway**
   - Kunjungi [railway.app](https://railway.app)
   - Sign up menggunakan akun GitHub

2. **Buat Project Baru**
   - Klik "New Project"
   - Pilih "Deploy from GitHub repo"
   - Pilih repository ini

3. **Set Environment Variables**
   
   Di Railway Dashboard → Service → Variables, tambahkan:
   ```
   APP_NAME=Sistem Perizinan Reklame
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:xxxxx
   APP_LOCALE=id
   APP_TIMEZONE=Asia/Jakarta
   
   DB_CONNECTION=sqlite
   
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   
   LOG_CHANNEL=stack
   LOG_LEVEL=error
   ```

4. **Deploy**
   - Railway akan otomatis build dan deploy

### File Konfigurasi Railway

Repository ini sudah dilengkapi dengan:
- `railway.json` - Pengaturan deploy Railway
- `nixpacks.toml` - Alternatif konfigurasi Nixpacks
- `Procfile` - Command untuk menjalankan aplikasi
- `railway-start.sh` - Script startup dengan migrasi otomatis
- `.env.railway.example` - Contoh variabel environment

---

## 🏛️ Deploy ke Hosting Pemerintahan

Untuk production di hosting pemerintahan, Anda mungkin perlu:

1. **Pastikan environment production**
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Gunakan database yang disediakan** (MySQL/PostgreSQL)
   ```
   DB_CONNECTION=mysql
   DB_HOST=your-host
   DB_PORT=3306
   DB_DATABASE=your-database
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   ```

3. **Jalankan optimasi**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Setup storage link**
   ```bash
   php artisan storage:link
   ```

5. **Jalankan migrasi dan seeder**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@perizinan.com | password |
| Kabid | kabid@perizinan.com | password |
| Kasi | kasi@perizinan.com | password |
| Operator 1 | operator1@perizinan.com | password |
| Operator 2 | operator2@perizinan.com | password |
| User | user@perizinan.com | password |

> ⚠️ **PENTING**: Ubah password semua akun default sebelum production!

## Teknologi

- **Framework**: Laravel 12
- **Database**: SQLite (default), MySQL/PostgreSQL (supported)
- **CSS**: Tailwind CSS
- **Maps**: Leaflet.js untuk pemilihan lokasi GPS

## Struktur Approval

```
User (Pengajuan)
    ↓
Operator (Review + Claim System)
    ↓
Kasi Perizinan (Review)
    ↓
Kabid Penyelenggaraan (Final Approval)
    ↓
Selesai (Nomor Izin Diterbitkan)
```

## Sistem Klaim Operator

Untuk mencegah beberapa operator mereview satu permohonan yang sama, sistem ini menggunakan mekanisme klaim dengan database locking:

1. Operator harus "mengambil" permohonan sebelum mereview
2. Permohonan yang sudah diambil tidak dapat diambil oleh operator lain
3. Operator dapat "melepaskan" permohonan jika tidak jadi mereview

## License

MIT License
