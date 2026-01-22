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

7. Buat symbolic link untuk storage
```bash
php artisan storage:link
```

8. Jalankan server
```bash
php artisan serve
```

9. Akses aplikasi di `http://localhost:8000`

---

## 🚀 Deploy ke Railway (Free Tier)

Railway menyediakan hosting gratis yang cocok untuk testing. Berikut langkah-langkah deploy:

### Cara 1: Deploy Otomatis (Recommended)

1. **Fork/Push Repository ke GitHub**
   - Pastikan repository sudah ada di GitHub Anda

2. **Buat Akun Railway**
   - Kunjungi [railway.app](https://railway.app)
   - Sign up menggunakan akun GitHub

3. **Buat Project Baru**
   - Klik "New Project"
   - Pilih "Deploy from GitHub repo"
   - Pilih repository `magang` Anda

4. **Set Environment Variables**
   
   Di Railway Dashboard → Service → Variables, tambahkan:
   ```
   APP_NAME=Sistem Perizinan Reklame
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:xxxxx (generate dengan: php artisan key:generate --show)
   APP_LOCALE=id
   APP_TIMEZONE=Asia/Jakarta
   
   DB_CONNECTION=sqlite
   
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   
   LOG_CHANNEL=stack
   LOG_LEVEL=error
   ```

5. **Generate APP_KEY**
   - Jalankan lokal: `php artisan key:generate --show`
   - Copy hasilnya ke variabel `APP_KEY` di Railway

6. **Deploy**
   - Railway akan otomatis build dan deploy
   - Setelah selesai, klik "Generate Domain" untuk mendapat URL publik

### Cara 2: Dengan PostgreSQL Database (Production Ready)

Untuk production, gunakan PostgreSQL:

1. Di Railway Dashboard, klik "+ New" → "Database" → "Add PostgreSQL"

2. Update Environment Variables:
   ```
   DB_CONNECTION=pgsql
   DATABASE_URL=${{Postgres.DATABASE_URL}}
   ```

3. Railway akan otomatis menghubungkan ke database

### File Konfigurasi Railway

Repository ini sudah dilengkapi dengan:
- `Dockerfile` - Konfigurasi container Docker
- `railway.json` - Pengaturan deploy Railway
- `nixpacks.toml` - Alternatif konfigurasi Nixpacks
- `Procfile` - Command untuk menjalankan aplikasi
- `railway-start.sh` - Script startup dengan migrasi otomatis
- `.env.railway.example` - Contoh variabel environment untuk Railway

### Catatan Penting Railway Free Tier

- **$5 kredit gratis per bulan** - cukup untuk testing
- **Sleep setelah idle** - Aplikasi akan sleep jika tidak diakses
- **Database SQLite** - Untuk testing (data reset saat redeploy)
- **PostgreSQL** - Gunakan untuk data persisten

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
