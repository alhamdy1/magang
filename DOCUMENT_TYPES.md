# Document Types Reference

## Valid Document Types untuk Upload

Berikut adalah tipe dokumen yang valid untuk sistem perizinan reklame:

| Document Type Code | Label (Indonesia) | Required |
|-------------------|-------------------|----------|
| `ktp` | Foto Kopi KTP Berwarna | ✅ Ya |
| `npwp` | Foto Copy NPWP Berwarna | ✅ Ya |
| `akte_pendirian` | Foto Copy Akte Pendirian | ✅ Ya |
| `retribusi_pajak` | Foto Copy Retribusi Pajak Reklame | ✅ Ya |
| `data_pemohon` | Data Isian Pemohon | ✅ Ya |
| `surat_pernyataan` | Surat Pernyataan Pertanggung Jawaban Konstruksi | ✅ Ya |
| `foto_kondisi` | Foto Kondisi dan Gambar Tampilan Visualisasi Reklame | ✅ Ya |
| `gambar_konstruksi` | Gambar Konstruksi Bidangan | ✅ Ya |
| `surat_permohonan` | Surat Permohonan Izin | ✅ Ya |
| `surat_kuasa` | Surat Kuasa | ❌ Opsional |

## Mapping di Controller

**Guest Permit Controller:**
```php
$documentTypes = [
    'doc_ktp' => 'ktp',
    'doc_npwp' => 'npwp',
    'doc_akte' => 'akte_pendirian',
    'doc_retribusi' => 'retribusi_pajak',
    'doc_data_isian' => 'data_pemohon',
    'doc_pernyataan' => 'surat_pernyataan',
    'doc_foto_reklame' => 'foto_kondisi',
    'doc_konstruksi' => 'gambar_konstruksi',
    'doc_permohonan' => 'surat_permohonan',
    'doc_kuasa' => 'surat_kuasa',
];
```

## Database Constraint

Tabel `documents` memiliki CHECK constraint yang memvalidasi `document_type`:

```sql
CHECK ("document_type" IN (
    'ktp', 
    'npwp', 
    'akte_pendirian', 
    'retribusi_pajak', 
    'data_pemohon', 
    'surat_pernyataan', 
    'foto_kondisi', 
    'gambar_konstruksi', 
    'surat_permohonan', 
    'surat_kuasa'
))
```

## Upload Limits

- **File size maksimal (umum):** 5 MB
- **File size maksimal (foto & konstruksi):** 10 MB
- **Total POST size:** 25 MB
- **Max file uploads:** 20 file
- **Format yang diterima:** JPG, JPEG, PNG, PDF

## Perbaikan yang Dilakukan (2026-02-13)

### Issue
Error saat submit form: `SQLSTATE[23000]: Integrity constraint violation: 19 CHECK constraint failed: document_type`

### Root Cause
Controller menggunakan format document_type yang tidak sesuai dengan enum di database:
- ❌ Controller menggunakan: `'KTP'`, `'NPWP'`, `'Akte Pendirian'` (uppercase, with spaces)
- ✅ Database expects: `'ktp'`, `'npwp'`, `'akte_pendirian'` (lowercase, with underscores)

### Solution
Updated `app/Http/Controllers/Guest/PermitController.php`:
- Changed all document_type values to match database enum values
- Menggunakan lowercase dan underscore sesuai standar database

### Files Modified
- `app/Http/Controllers/Guest/PermitController.php` - Fixed document_type mapping
- `public/.user.ini` - Increased upload limits
- `public/.htaccess` - Added PHP upload configuration
- `public/index.php` - Added runtime ini_set for upload limits
