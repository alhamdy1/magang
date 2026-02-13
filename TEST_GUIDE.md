# 🚀 Panduan Testing Lokal - Sistem Perizinan Reklame

## ✅ Setup Sudah Selesai!

Proyek telah berhasil di-setup dengan:
- ✓ Dependencies PHP & Node.js terinstall
- ✓ Database SQLite dibuat dan migrasi selesai
- ✓ Data testing user sudah di-seed
- ✓ Assets frontend sudah di-build
- ✓ Storage directories sudah dikonfigurasi

---

## 🔐 Akun Testing yang Tersedia

Semua password: `password`

| Role | Email | Deskripsi |
|------|-------|-----------|
| **Admin** | admin@perizinan.com | Manajemen pengguna sistem |
| **Kabid** | kabid@perizinan.com | Persetujuan final & penerbitan nomor izin |
| **Kasi** | kasi@perizinan.com | Review tingkat supervisor |
| **Operator 1** | operator1@perizinan.com | Review awal permohonan |
| **Operator 2** | operator2@perizinan.com | Review awal permohonan |
| **User** | user@perizinan.com | Pemohon/pengaju izin reklame |

---

## 🎯 Cara Menjalankan Aplikasi

### Opsi 1: Mode Development (Recommended untuk Testing)
```bash
php artisan serve
```
Aplikasi akan berjalan di: **http://localhost:8000**

### Opsi 2: Development dengan Auto-reload Frontend
Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

### Opsi 3: Menggunakan composer script
```bash
composer dev
```
Script ini akan menjalankan:
- Laravel server (port 8000)
- Queue worker
- Log viewer (Pail)
- Vite dev server (hot reload)

---

## 🧪 Skenario Testing

### 1️⃣ Testing sebagai User (Pemohon)

**Login sebagai User:**
- Email: `user@perizinan.com`
- Password: `password`

**Langkah Testing:**
1. Klik "Ajukan Izin Baru"
2. Isi form permohonan:
   - Nama: "PT Advertising Indonesia"
   - Alamat: "Jl. Raya Bandung No. 123"
   - Telepon: "022-1234567"
   - Klasifikasi: Pilih "Permanen" atau "Non-Permanen"
   - Ukuran: "4m x 6m"
   - Narasi: "Billboard promosi produk"
   - Lokasi: Klik peta untuk set koordinat GPS
3. Upload dokumen-dokumen yang diperlukan
4. Submit permohonan
5. Cek status di "Daftar Permohonan Saya"
6. Klik "Track Status" untuk lihat progress real-time

**Yang Diharapkan:**
- Status berubah menjadi "Submitted" → "Operator Review"
- Bisa melihat timeline approval
- Bisa tracking lokasi reklame di peta

---

### 2️⃣ Testing sebagai Operator

**Login sebagai Operator:**
- Email: `operator1@perizinan.com`
- Password: `password`

**Langkah Testing:**
1. Lihat daftar permohonan yang belum di-claim
2. Klik "Claim" pada permohonan yang ingin direview
3. Review dokumen dan data permohonan
4. Beri catatan di "Notes"
5. Pilih:
   - **Approve**: Permohonan lanjut ke Kasi
   - **Reject**: Permohonan ditolak dengan alasan

**Testing Sistem Claim:**
- Login sebagai `operator2@perizinan.com` di browser lain/incognito
- Cek bahwa permohonan yang sudah di-claim operator1 tidak bisa di-claim lagi
- Operator1 bisa "Release Claim" jika diperlukan

**Yang Diharapkan:**
- Hanya 1 operator yang bisa handle 1 permohonan
- Status update ke "Operator Approved/Rejected"
- Catatan tersimpan di approval history

---

### 3️⃣ Testing sebagai Kasi

**Login sebagai Kasi:**
- Email: `kasi@perizinan.com`
- Password: `password`

**Langkah Testing:**
1. Lihat permohonan yang sudah diapprove Operator
2. Review data dan catatan dari Operator
3. Tambahkan catatan Kasi
4. Approve atau Reject

**Yang Diharapkan:**
- Hanya melihat permohonan dengan status "Operator Approved"
- Bisa lihat timeline approval sebelumnya
- Status update ke "Kasi Approved/Rejected"

---

### 4️⃣ Testing sebagai Kabid (Final Approval)

**Login sebagai Kabid:**
- Email: `kabid@perizinan.com`
- Password: `password`

**Langkah Testing:**
1. Lihat permohonan yang sudah diapprove Kasi
2. Review seluruh data dan history approval
3. Jika Approve:
   - Sistem generate nomor izin otomatis
   - Status menjadi "Completed"
   - Pemohon bisa download izin
4. Jika Reject:
   - Status menjadi "Kabid Rejected"
   - Permohonan selesai (ditolak)

**Yang Diharapkan:**
- Auto-generate permit number (format: PERM-YYYYMMDD-XXXX)
- Status final: "Completed" atau "Kabid Rejected"
- Pemohon mendapat notifikasi

---

### 5️⃣ Testing sebagai Admin

**Login sebagai Admin:**
- Email: `admin@perizinan.com`
- Password: `password`

**Langkah Testing:**
1. Manage Users:
   - Create user baru dengan role tertentu
   - Edit user existing
   - Delete user
2. View dashboard statistics

**Yang Diharapkan:**
- CRUD operations pada users
- Validasi email unique
- Role assignment berfungsi

---

## 🔍 Fitur-Fitur yang Harus Ditest

### ✅ Checklist Testing

#### Autentikasi & Otorisasi
- [ ] Login dengan berbagai role
- [ ] Register user baru
- [ ] Logout
- [ ] Redirect ke dashboard sesuai role
- [ ] Access control (user role X tidak bisa akses halaman role Y)

#### Permohonan Izin (User)
- [ ] Create permohonan baru
- [ ] Upload multiple dokumen
- [ ] Pilih lokasi di peta (GPS coordinates)
- [ ] View daftar permohonan sendiri
- [ ] Track status permohonan
- [ ] View detail permohonan

#### Review Operator
- [ ] Claim permohonan
- [ ] Release claim
- [ ] Approve dengan notes
- [ ] Reject dengan notes
- [ ] Sistem prevent double claim

#### Review Kasi
- [ ] View permohonan yang sudah approve operator
- [ ] Approve/Reject dengan notes
- [ ] View history approval

#### Review Kabid
- [ ] View permohonan yang sudah approve kasi
- [ ] Approve → generate permit number
- [ ] Reject dengan notes
- [ ] View complete history

#### Status & Tracking
- [ ] Status tracking real-time
- [ ] Timeline approval
- [ ] Notes dari setiap reviewer visible
- [ ] Email notifications (jika sudah konfigurasi mail)

#### Upload Dokumen
- [ ] Upload file (PDF, JPG, PNG)
- [ ] View uploaded documents
- [ ] Download documents
- [ ] Validasi file type & size

#### Peta & Lokasi
- [ ] Click map untuk set koordinat
- [ ] Koordinat tersimpan dengan benar
- [ ] Display lokasi di map (view mode)
- [ ] Drag marker untuk update lokasi

---

## 🛠️ Commands Berguna untuk Testing

### Reset Database & Start Fresh
```bash
php artisan migrate:fresh --seed
```
⚠️ WARNING: Ini akan menghapus semua data dan reset ke kondisi awal!

### View Database (SQLite)
```bash
php artisan tinker
# Kemudian jalankan:
\App\Models\User::all();
\App\Models\Permit::all();
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Run Tests (jika ada)
```bash
php artisan test
```

---

## 🐛 Troubleshooting

### Error: Permission Denied
```bash
chmod -R 775 storage bootstrap/cache
```

### Error: Database Locked
```bash
php artisan cache:clear
# Restart server
```

### Error: Class not found
```bash
composer dump-autoload
```

### Frontend tidak update
```bash
npm run build
# atau untuk dev mode:
npm run dev
```

---

## 📊 Dashboard URLs

Setelah login, setiap role akan redirect ke:
- User: `http://localhost:8000/user/dashboard`
- Operator: `http://localhost:8000/operator/dashboard`
- Kasi: `http://localhost:8000/kasi/dashboard`
- Kabid: `http://localhost:8000/kabid/dashboard`
- Admin: `http://localhost:8000/admin/dashboard`

---

## 🎬 Flow Testing Lengkap (End-to-End)

1. **User** submit permohonan → Status: "Submitted"
2. **Operator1** claim → Review → Approve → Status: "Operator Approved"
3. **Kasi** review → Approve → Status: "Kasi Approved"
4. **Kabid** review → Approve → Status: "Completed" + Permit Number generated
5. **User** bisa download izin yang sudah approved

Atau jika ditolak di salah satu tahap:
- Status menjadi "Operator Rejected" / "Kasi Rejected" / "Kabid Rejected"
- Permohonan tidak bisa dilanjutkan
- User bisa lihat alasan penolakan di notes

---

## 📝 Tips Testing

1. **Gunakan Multiple Browsers/Incognito** untuk test multiple users bersamaan
2. **Test sistem claim** dengan 2 operator secara simultan
3. **Test rejection flow** di berbagai tahap
4. **Test dengan data invalid** untuk memastikan validasi berfungsi
5. **Test upload dokumen** dengan berbagai format dan ukuran
6. **Test responsive design** di mobile/tablet

---

## ✨ Selamat Testing!

Jika menemukan bug atau punya pertanyaan, catat dan laporkan! 🐛

**Happy Testing! 🚀**
