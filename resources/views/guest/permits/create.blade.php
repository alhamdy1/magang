@extends('layouts.app')

@section('title', 'Ajukan Permohonan Izin (Tanpa Akun) - Sistem Perizinan Reklame')

@push('styles')
<style>
    #map { height: 400px; width: 100%; border-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Ajukan Permohonan Izin Reklame</h1>
    <p class="text-gray-600">Lengkapi formulir di bawah ini untuk mengajukan permohonan izin reklame tanpa perlu membuat akun</p>
    
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-medium">Informasi Penting</p>
                <ul class="mt-1 list-disc list-inside">
                    <li>Anda akan menerima <strong>Nomor Tracking</strong> setelah mengirim permohonan</li>
                    <li>Simpan nomor tracking untuk melacak status permohonan</li>
                    <li>Notifikasi akan dikirim ke email yang Anda daftarkan</li>
                    <li>Sudah punya akun? <a href="{{ route('login') }}" class="underline font-medium">Masuk di sini</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Download Template Section -->
    <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <div class="text-sm text-green-800">
                <p class="font-medium mb-2">📄 Download Template Surat Permohonan</p>
                <p class="mb-2">Sebelum mengisi formulir, download dan lengkapi template surat berikut:</p>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ asset('assets/templates/fom reklame-halaman-halaman-1.pdf') }}" 
                       target="_blank"
                       download="Surat_Pernyataan.pdf"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Download Surat Pernyataan
                    </a>
                    <a href="{{ asset('assets/templates/fom reklame-halaman-halaman-3.pdf') }}" 
                       target="_blank"
                       download="Surat_Kuasa.pdf"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Download Surat Kuasa
                    </a>
                </div>
                <p class="mt-2 text-xs">💡 <strong>Petunjuk:</strong> Cetak, isi, tanda tangani, scan, lalu upload di bagian dokumen pendukung pada formulir di bawah.</p>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    {{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('guest.permits.store') }}" enctype="multipart/form-data" class="space-y-8">
    @csrf
    
    <!-- Informasi Kontak (Guest) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Informasi Kontak (untuk Tracking & Notifikasi)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email') }}" required
                    placeholder="email@contoh.com"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('guest_email') border-red-500 @enderror">
                @error('guest_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Notifikasi status akan dikirim ke email ini</p>
            </div>
            
            <div>
                <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="text" name="guest_phone" id="guest_phone" value="{{ old('guest_phone') }}" required
                    placeholder="08xxxxxxxxxx"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('guest_phone') border-red-500 @enderror">
                @error('guest_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="guest_nik" class="block text-sm font-medium text-gray-700 mb-1">NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span></label>
                <input type="text" name="guest_nik" id="guest_nik" value="{{ old('guest_nik') }}" required
                    maxlength="16" pattern="[0-9]{16}"
                    placeholder="16 digit NIK"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('guest_nik') border-red-500 @enderror">
                @error('guest_nik')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Digunakan untuk verifikasi saat tracking</p>
            </div>
        </div>
    </div>
    
    <!-- Data Pemohon -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Data Pemohon</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama_pemohon" class="block text-sm font-medium text-gray-700 mb-1">Nama/Badan/Organisasi <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pemohon" id="nama_pemohon" value="{{ old('nama_pemohon') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nama_pemohon') border-red-500 @enderror">
                @error('nama_pemohon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="nik_pemohon" class="block text-sm font-medium text-gray-700 mb-1">NIK Pemohon <span class="text-red-500">*</span></label>
                <input type="text" name="nik_pemohon" id="nik_pemohon" value="{{ old('nik_pemohon') }}" required
                    maxlength="16" pattern="[0-9]{16}"
                    placeholder="16 digit NIK"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nik_pemohon') border-red-500 @enderror">
                @error('nik_pemohon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Pemohon <span class="text-red-500">*</span></label>
                <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nomor_telepon') border-red-500 @enderror">
                @error('nomor_telepon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pemohon <span class="text-red-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Data Reklame -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Data Reklame</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="klasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Jenis/Klasifikasi <span class="text-red-500">*</span></label>
                <select name="klasifikasi" id="klasifikasi" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('klasifikasi') border-red-500 @enderror">
                    <option value="">Pilih Klasifikasi</option>
                    <option value="permanen" {{ old('klasifikasi') === 'permanen' ? 'selected' : '' }}>Permanen</option>
                    <option value="non_permanen" {{ old('klasifikasi') === 'non_permanen' ? 'selected' : '' }}>Non Permanen</option>
                </select>
                @error('klasifikasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="ukuran_jumlah" class="block text-sm font-medium text-gray-700 mb-1">Ukuran/Jumlah <span class="text-red-500">*</span></label>
                <input type="text" name="ukuran_jumlah" id="ukuran_jumlah" value="{{ old('ukuran_jumlah') }}" required
                    placeholder="Contoh: 3m x 5m, 2 unit"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('ukuran_jumlah') border-red-500 @enderror">
                @error('ukuran_jumlah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="narasi" class="block text-sm font-medium text-gray-700 mb-1">Narasi/Deskripsi Reklame <span class="text-red-500">*</span></label>
                <textarea name="narasi" id="narasi" rows="4" required
                    placeholder="Jelaskan secara detail mengenai reklame yang akan dipasang..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('narasi') border-red-500 @enderror">{{ old('narasi') }}</textarea>
                @error('narasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Lokasi Reklame -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Lokasi Reklame (Presisi GPS)</h2>
        <p class="text-sm text-gray-500 mb-4">Klik pada peta untuk menentukan lokasi reklame atau gunakan tombol "Gunakan Lokasi Saya" untuk lokasi saat ini.</p>
        
        <!-- Search Location -->
        <div class="mb-4">
            <label for="searchLocation" class="block text-sm font-medium text-gray-700 mb-1">Cari Lokasi</label>
            <div class="flex gap-2">
                <input type="text" id="searchLocation" placeholder="Ketik nama tempat (contoh: Bangkalan, Jawa Timur)" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <button type="button" id="searchBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
            </div>
        </div>
        
        <div class="mb-4">
            <button type="button" id="getLocationBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Gunakan Lokasi Saya
            </button>
        </div>
        
        <div id="map" class="mb-4"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude <span class="text-red-500">*</span></label>
                <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" required readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 @error('latitude') border-red-500 @enderror">
                @error('latitude')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-1">
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude <span class="text-red-500">*</span></label>
                <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" required readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 @error('longitude') border-red-500 @enderror">
                @error('longitude')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-1">
                <label for="lokasi_alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lokasi <span class="text-red-500">*</span></label>
                <input type="text" name="lokasi_alamat" id="lokasi_alamat" value="{{ old('lokasi_alamat') }}" required
                    placeholder="Alamat lokasi reklame"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('lokasi_alamat') border-red-500 @enderror">
                @error('lokasi_alamat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Dokumen Upload -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Dokumen Persyaratan</h2>
        <p class="text-sm text-gray-500 mb-4">Upload dokumen dalam format JPG, JPEG, PNG, atau PDF. Ukuran maksimal 5MB per file.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="doc_ktp" class="block text-sm font-medium text-gray-700 mb-1">Foto Kopi KTP Berwarna <span class="text-red-500">*</span></label>
                <input type="file" name="doc_ktp" id="doc_ktp" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_ktp') border-red-500 @enderror">
                @error('doc_ktp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_npwp" class="block text-sm font-medium text-gray-700 mb-1">Foto Copy NPWP Berwarna <span class="text-red-500">*</span></label>
                <input type="file" name="doc_npwp" id="doc_npwp" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_npwp') border-red-500 @enderror">
                @error('doc_npwp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_akte" class="block text-sm font-medium text-gray-700 mb-1">Foto Copy Akte Pendirian <span class="text-red-500">*</span></label>
                <input type="file" name="doc_akte" id="doc_akte" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_akte') border-red-500 @enderror">
                @error('doc_akte')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_retribusi" class="block text-sm font-medium text-gray-700 mb-1">Foto Copy Retribusi Pajak Reklame <span class="text-red-500">*</span></label>
                <input type="file" name="doc_retribusi" id="doc_retribusi" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_retribusi') border-red-500 @enderror">
                @error('doc_retribusi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_data_isian" class="block text-sm font-medium text-gray-700 mb-1">Data Isian Pemohon <span class="text-red-500">*</span></label>
                <input type="file" name="doc_data_isian" id="doc_data_isian" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_data_isian') border-red-500 @enderror">
                @error('doc_data_isian')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_pernyataan" class="block text-sm font-medium text-gray-700 mb-1">Surat Pernyataan Pertanggung Jawaban Konstruksi <span class="text-red-500">*</span></label>
                <input type="file" name="doc_pernyataan" id="doc_pernyataan" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_pernyataan') border-red-500 @enderror">
                @error('doc_pernyataan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_foto_reklame" class="block text-sm font-medium text-gray-700 mb-1">Foto Kondisi dan Gambar Tampilan Visualisasi Reklame <span class="text-red-500">*</span></label>
                <input type="file" name="doc_foto_reklame" id="doc_foto_reklame" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_foto_reklame') border-red-500 @enderror">
                @error('doc_foto_reklame')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_konstruksi" class="block text-sm font-medium text-gray-700 mb-1">Gambar Konstruksi Bidangan <span class="text-red-500">*</span></label>
                <input type="file" name="doc_konstruksi" id="doc_konstruksi" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_konstruksi') border-red-500 @enderror">
                @error('doc_konstruksi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_permohonan" class="block text-sm font-medium text-gray-700 mb-1">Surat Permohonan Izin <span class="text-red-500">*</span></label>
                <input type="file" name="doc_permohonan" id="doc_permohonan" required accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_permohonan') border-red-500 @enderror">
                @error('doc_permohonan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="doc_kuasa" class="block text-sm font-medium text-gray-700 mb-1">Surat Kuasa <span class="text-gray-400">(Opsional)</span></label>
                <input type="file" name="doc_kuasa" id="doc_kuasa" accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm @error('doc_kuasa') border-red-500 @enderror">
                @error('doc_kuasa')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Submit Button -->
    <div class="flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
            ← Kembali ke Beranda
        </a>
        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
            Ajukan Permohonan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Auto-fill NIK pemohon from guest NIK
    document.getElementById('guest_nik').addEventListener('input', function() {
        var nikPemohon = document.getElementById('nik_pemohon');
        if (!nikPemohon.value) {
            nikPemohon.value = this.value;
        }
    });
    
    // Auto-fill nomor telepon from guest phone
    document.getElementById('guest_phone').addEventListener('input', function() {
        var nomorTelepon = document.getElementById('nomor_telepon');
        if (!nomorTelepon.value) {
            nomorTelepon.value = this.value;
        }
    });
    
    // Initialize map centered on Indonesia
    var map = L.map('map').setView([-6.2088, 106.8456], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    var marker;
    
    // Function to set marker
    function setMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        
        // Allow marker drag
        marker.on('dragend', function(e) {
            var pos = e.target.getLatLng();
            document.getElementById('latitude').value = pos.lat.toFixed(8);
            document.getElementById('longitude').value = pos.lng.toFixed(8);
        });
    }
    
    // Click on map to set location
    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });
    
    // Search location function
    function searchLocation(query) {
        if (!query) {
            alert('Masukkan nama lokasi');
            return;
        }
        
        document.getElementById('searchBtn').disabled = true;
        document.getElementById('searchBtn').innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mencari...';
        
        // Use Nominatim (OpenStreetMap) geocoding
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('searchBtn').disabled = false;
                document.getElementById('searchBtn').innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Cari';
                
                if (data && data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lng = parseFloat(data[0].lon);
                    setMarker(lat, lng);
                    map.setView([lat, lng], 15);
                } else {
                    alert('Lokasi tidak ditemukan. Coba dengan nama yang lebih spesifik.');
                }
            })
            .catch(error => {
                document.getElementById('searchBtn').disabled = false;
                document.getElementById('searchBtn').innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Cari';
                alert('Error mencari lokasi: ' + error.message);
            });
    }
    
    // Search button click
    document.getElementById('searchBtn').addEventListener('click', function() {
        var query = document.getElementById('searchLocation').value;
        searchLocation(query);
    });
    
    // Search on Enter key
    document.getElementById('searchLocation').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchLocation(this.value);
        }
    });
    
    // Get current location button
    document.getElementById('getLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            this.disabled = true;
            this.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mendapatkan lokasi...';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    setMarker(lat, lng);
                    map.setView([lat, lng], 17);
                    document.getElementById('getLocationBtn').disabled = false;
                    document.getElementById('getLocationBtn').innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Gunakan Lokasi Saya';
                },
                function(error) {
                    alert('Error getting location: ' + error.message);
                    document.getElementById('getLocationBtn').disabled = false;
                    document.getElementById('getLocationBtn').innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Gunakan Lokasi Saya';
                },
                { enableHighAccuracy: true }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });
    
    // If there are old values, set them
    @if(old('latitude') && old('longitude'))
        setMarker({{ old('latitude') }}, {{ old('longitude') }});
        map.setView([{{ old('latitude') }}, {{ old('longitude') }}], 17);
    @endif
</script>
@endpush
