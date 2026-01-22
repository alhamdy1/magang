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

## Instalasi

### Persyaratan
- PHP >= 8.2
- Composer
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

6. Buat symbolic link untuk storage
```bash
php artisan storage:link
```

7. Jalankan server
```bash
php artisan serve
```

8. Akses aplikasi di `http://localhost:8000`

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@perizinan.com | password |
| Kabid | kabid@perizinan.com | password |
| Kasi | kasi@perizinan.com | password |
| Operator 1 | operator1@perizinan.com | password |
| Operator 2 | operator2@perizinan.com | password |
| User | user@perizinan.com | password |

## Teknologi

- **Framework**: Laravel 11
- **Database**: SQLite (default), MySQL/PostgreSQL (supported)
- **CSS**: Tailwind CSS (via CDN)
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
