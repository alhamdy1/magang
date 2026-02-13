# Dokumentasi Optimasi & Keamanan Sistem Perizinan Reklame

## Daftar Isi
1. [Ringkasan Perubahan](#ringkasan-perubahan)
2. [Keamanan](#keamanan)
3. [Performa](#performa)
4. [User Experience (UX)](#user-experience-ux)
5. [Komponen Blade Reusable](#komponen-blade-reusable)
6. [Helper Functions](#helper-functions)
7. [Validasi Kustom](#validasi-kustom)
8. [Logging](#logging)
9. [Error Handling](#error-handling)

---

## Ringkasan Perubahan

### Security Improvements
- ✅ Security Headers (CSP, X-Frame-Options, X-XSS-Protection, dll)
- ✅ Rate Limiting per IP dan per route
- ✅ Input Validation dengan Form Request classes
- ✅ Secure File Upload dengan validasi MIME type
- ✅ SQL Injection Prevention Rules
- ✅ XSS Protection dengan sanitisasi input
- ✅ CSRF Protection (built-in Laravel)
- ✅ Security Event Logging

### Performance Improvements
- ✅ Database Indexes untuk query yang sering digunakan
- ✅ Query Scopes untuk kode yang lebih bersih
- ✅ Caching untuk data yang sering diakses
- ✅ View Composers untuk data dashboard

### UX Improvements
- ✅ Komponen form yang accessible (ARIA labels, focus states)
- ✅ Loading overlay untuk form submission
- ✅ Toast notifications
- ✅ Confirm dialogs untuk aksi berbahaya
- ✅ Skip-to-content link untuk accessibility
- ✅ Error pages yang informatif (404, 403, 429, 500)
- ✅ Print styles untuk dokumen

---

## Keamanan

### Security Headers Middleware
**File:** `app/Http/Middleware/SecurityHeaders.php`

Headers yang ditambahkan:
- `X-Frame-Options: DENY` - Mencegah clickjacking
- `X-Content-Type-Options: nosniff` - Mencegah MIME sniffing
- `X-XSS-Protection: 1; mode=block` - Aktifkan XSS filter browser
- `Referrer-Policy: strict-origin-when-cross-origin` - Kontrol referrer
- `Permissions-Policy` - Disable unused browser features
- `Content-Security-Policy` (production only) - Kontrol resource loading

### Rate Limiting
**File:** `app/Http/Middleware/ThrottleByIp.php`

Konfigurasi di `routes/web.php`:
```php
// Tracking: 30 request per menit
Route::middleware('throttle:30,1')

// Guest permits: 10 request per menit
Route::middleware('throttle:10,1')

// Login: 5 attempts per menit
Route::middleware('throttle:5,1')
```

### Secure File Upload
**File:** `app/Services/SecureFileUploadService.php`

Fitur:
- Validasi MIME type yang sebenarnya (bukan hanya extension)
- Validasi konten file (magic bytes)
- Generate nama file yang aman dengan random string
- Pencegahan path traversal
- Ukuran file maksimal: 5MB

**Penggunaan:**
```php
use App\Services\SecureFileUploadService;

$uploadService = new SecureFileUploadService();
$path = $uploadService->upload($request->file('dokumen'), 'permits');
```

### Input Validation
**Files:**
- `app/Http/Requests/StorePermitRequest.php`
- `app/Http/Requests/StoreGuestPermitRequest.php`

Fitur:
- Sanitisasi input di `prepareForValidation()`
- Pesan error dalam Bahasa Indonesia
- Validasi NIK dengan format yang benar
- Validasi file dengan MIME type

---

## Performa

### Database Indexes
**File:** `database/migrations/2026_01_25_000001_add_performance_indexes.php`

Indexes yang ditambahkan:
```sql
-- Permits table
INDEX permits_status_index (status)
INDEX permits_tracking_number_index (tracking_number)
INDEX permits_user_id_index (user_id)
INDEX permits_claimed_by_index (claimed_by)
INDEX permits_guest_email_nik_index (guest_email, guest_nik)
INDEX permits_created_at_index (created_at)
INDEX permits_nik_pemohon_index (nik_pemohon)

-- Approval histories table
INDEX approval_histories_permit_id_index (permit_id)
INDEX approval_histories_user_id_index (user_id)

-- Users table
INDEX users_role_index (role)
INDEX users_nik_index (nik)
```

### Query Scopes
**File:** `app/Models/Permit.php`

```php
// Contoh penggunaan
Permit::status('pending_operator')->get();
Permit::pendingOperator()->get();
Permit::forUser($userId)->get();
Permit::forGuest($email, $nik)->get();
Permit::search($keyword)->get();
```

### Caching
```php
// Cache statistics for 5 minutes
$stats = Permit::getStatistics();

// Cache tracking lookup
$permit = Permit::findByTracking($trackingNumber);
```

---

## User Experience (UX)

### Loading Overlay
**File:** `resources/views/components/loading-overlay.blade.php`

**Penggunaan otomatis:**
```html
<form data-loading>
    <!-- Form akan menampilkan loading overlay saat submit -->
</form>
```

**Penggunaan manual:**
```javascript
showLoading();
// ... do something ...
hideLoading();
```

### Toast Notifications
**File:** `resources/views/components/toast.blade.php`

**Dari Controller:**
```php
return redirect()->back()->with('toast_success', 'Berhasil disimpan!');
return redirect()->back()->with('toast_error', 'Terjadi kesalahan!');
```

**Dari JavaScript:**
```javascript
showToast('Pesan sukses', 'success');
showToast('Pesan error', 'error');
showToast('Peringatan', 'warning');
showToast('Informasi', 'info');
```

### Confirm Dialog
**File:** `resources/views/components/confirm-dialog.blade.php`

```html
<x-confirm-dialog 
    id="delete-confirm"
    title="Hapus Data"
    message="Yakin ingin menghapus?"
    confirmText="Ya, Hapus"
    variant="danger"
    :formAction="route('permits.destroy', $permit)"
    formMethod="DELETE"
/>

<button onclick="openConfirmDialog('delete-confirm')">Hapus</button>
```

---

## Komponen Blade Reusable

### Form Input
```html
<x-form-input 
    name="email" 
    label="Email" 
    type="email" 
    required 
    placeholder="contoh@email.com"
    help="Masukkan email yang valid"
/>
```

### Form Textarea
```html
<x-form-textarea 
    name="keterangan" 
    label="Keterangan" 
    required 
    rows="4"
    maxlength="500"
/>
```

### Form Select
```html
<x-form-select 
    name="status" 
    label="Status" 
    required
    :options="['pending' => 'Pending', 'approved' => 'Approved']"
    selected="pending"
/>
```

### Form File Upload
```html
<x-form-file 
    name="dokumen" 
    label="Dokumen Pendukung" 
    accept=".pdf,.jpg,.png"
    help="Maks. 5MB"
/>
```

### Button
```html
<x-button type="submit" variant="primary">Simpan</x-button>
<x-button variant="secondary" href="/cancel">Batal</x-button>
<x-button variant="danger" loading>Menghapus...</x-button>
```

### Status Badge
```html
<x-status-badge status="pending" />
<x-status-badge status="approved" />
<x-status-badge status="rejected" />
```

### Card
```html
<x-card title="Judul" subtitle="Deskripsi">
    Konten card
</x-card>
```

### Modal
```html
<x-modal id="my-modal" title="Judul Modal">
    Konten modal
    <x-slot name="footer">
        <x-button onclick="closeModal('my-modal')">Tutup</x-button>
    </x-slot>
</x-modal>
```

### Breadcrumb
```html
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Detail']
]" />
```

### Empty State
```html
<x-empty-state 
    title="Belum ada data" 
    description="Data tidak ditemukan"
    icon="document"
>
    <x-button href="/create">Buat Baru</x-button>
</x-empty-state>
```

### Alert
```php
// Dari controller
return redirect()->back()->with('success', 'Berhasil!');
return redirect()->back()->with('error', 'Gagal!');
return redirect()->back()->with('warning', 'Peringatan!');
return redirect()->back()->with('info', 'Informasi');
```

---

## Helper Functions

### DateHelper
**File:** `app/Helpers/DateHelper.php`

```php
use App\Helpers\DateHelper;

DateHelper::formatIndonesian($date, 'full');    // Senin, 25 Januari 2025
DateHelper::formatIndonesian($date, 'medium');  // 25 Januari 2025
DateHelper::formatIndonesian($date, 'short');   // 25 Jan 2025
DateHelper::formatIndonesian($date, 'datetime'); // 25 Januari 2025, 14:30
DateHelper::formatIndonesian($date, 'relative'); // 2 jam yang lalu
```

**Blade Directive:**
```html
@dateindo($permit->created_at, 'full')
```

### FormatHelper
**File:** `app/Helpers/FormatHelper.php`

```php
use App\Helpers\FormatHelper;

FormatHelper::rupiah(1500000);           // Rp 1.500.000
FormatHelper::nik('3201234567890001');   // 3201 2345 6789 0001
FormatHelper::phone('081234567890');     // +62 812-3456-7890
FormatHelper::fileSize(1048576);         // 1 MB
FormatHelper::truncate($text, 100);      // Text with max 100 chars...
FormatHelper::maskEmail('user@email.com'); // us**@email.com
```

**Blade Directives:**
```html
@rupiah($amount)
@nik($permit->nik_pemohon)
@phone($permit->telepon)
@truncate($text, 50)
```

### ValidationHelper
**File:** `app/Helpers/ValidationHelper.php`

```php
use App\Helpers\ValidationHelper;

ValidationHelper::isValidNIK('3201234567890001');  // true/false
ValidationHelper::parseNIK('3201234567890001');    // ['province_code' => '32', ...]
ValidationHelper::isValidPhoneNumber('081234567890'); // true/false
ValidationHelper::sanitizeFilename($filename);     // Safe filename
```

---

## Validasi Kustom

### ValidNIK Rule
**File:** `app/Rules/ValidNIK.php`

```php
use App\Rules\ValidNIK;

$request->validate([
    'nik' => ['required', 'string', 'size:16', new ValidNIK],
]);
```

### IndonesianPhone Rule
**File:** `app/Rules/IndonesianPhone.php`

```php
use App\Rules\IndonesianPhone;

$request->validate([
    'telepon' => ['required', new IndonesianPhone],
]);
```

### NoSQLInjection Rule
**File:** `app/Rules/NoSQLInjection.php`

```php
use App\Rules\NoSQLInjection;

$request->validate([
    'search' => ['string', new NoSQLInjection],
]);
```

---

## Logging

### Log Channels
**File:** `config/logging.php`

Channels yang ditambahkan:
- `security` - Log untuk event keamanan (90 hari retention)
- `permits` - Log untuk aktivitas perizinan (365 hari retention)

**Penggunaan:**
```php
use Illuminate\Support\Facades\Log;

Log::channel('security')->warning('Suspicious login attempt', [
    'ip' => request()->ip(),
    'email' => $email,
]);

Log::channel('permits')->info('Permit approved', [
    'permit_id' => $permit->id,
    'approved_by' => auth()->id(),
]);
```

---

## Error Handling

### Custom Exception Handler
**File:** `app/Exceptions/Handler.php`

Fitur:
- Pesan error dalam Bahasa Indonesia
- Logging error yang terstruktur
- Throttle exception handling (429)

### Error Pages
**Files:**
- `resources/views/errors/404.blade.php` - Halaman tidak ditemukan
- `resources/views/errors/403.blade.php` - Akses ditolak
- `resources/views/errors/429.blade.php` - Terlalu banyak request
- `resources/views/errors/500.blade.php` - Server error

---

## Cara Testing

### 1. Test Rate Limiting
```bash
# Jalankan ini berkali-kali dengan cepat
curl -X POST http://localhost:8000/login -d "email=test@test.com&password=test"
```

### 2. Test Security Headers
```bash
curl -I http://localhost:8000
# Harus menampilkan X-Frame-Options, X-XSS-Protection, dll
```

### 3. Test File Upload
Upload file dengan extension yang salah (misal file.exe diubah ke file.pdf) - harus ditolak.

### 4. Test Validation
Submit form dengan NIK tidak valid - harus menampilkan error.

---

## Catatan Penting

1. **Environment Production**: Pastikan `APP_DEBUG=false` di production
2. **HTTPS**: CSP policy hanya aktif di production dengan HTTPS
3. **Cache**: Jalankan `php artisan config:cache` dan `php artisan route:cache` di production
4. **Indexes**: Migration untuk indexes sudah dijalankan secara otomatis

---

*Dokumentasi ini dibuat sebagai bagian dari optimasi sistem perizinan reklame.*
