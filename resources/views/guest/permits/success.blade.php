@extends('layouts.app')

@section('title', 'Permohonan Berhasil Diajukan - Sistem Perizinan Reklame')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Success Header -->
        <div class="bg-green-500 px-6 py-8 text-center">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Permohonan Berhasil Diajukan!</h1>
            <p class="text-green-100 mt-2">Permohonan izin reklame Anda telah diterima</p>
        </div>
        
        <!-- Tracking Number Section -->
        <div class="px-6 py-8">
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 text-center mb-6">
                <p class="text-sm text-blue-600 font-medium mb-2">Nomor Tracking Anda</p>
                <p class="text-3xl font-bold text-blue-800 font-mono tracking-wider" id="trackingNumber">
                    {{ $trackingNumber }}
                </p>
                <button onclick="copyTrackingNumber()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span id="copyBtnText">Salin Nomor Tracking</span>
                </button>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold">PENTING: Simpan nomor tracking ini!</p>
                        <p class="mt-1">Anda membutuhkan nomor ini untuk melacak status permohonan. Screenshot atau catat nomor ini di tempat yang aman.</p>
                    </div>
                </div>
            </div>
            
            @if($permit)
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Permohonan</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nama Pemohon</dt>
                        <dd class="text-gray-900 font-medium">{{ $permit->nama_pemohon }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">NIK Pemohon</dt>
                        <dd class="text-gray-900 font-medium">{{ $permit->nik_pemohon }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email Notifikasi</dt>
                        <dd class="text-gray-900 font-medium">{{ $permit->guest_email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Klasifikasi</dt>
                        <dd class="text-gray-900 font-medium">{{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Menunggu Review
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tanggal Pengajuan</dt>
                        <dd class="text-gray-900 font-medium">{{ $permit->created_at->format('d M Y, H:i') }} WIB</dd>
                    </div>
                </dl>
            </div>
            @endif
            
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Langkah Selanjutnya</h3>
                <ol class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0">1</span>
                        <span>Simpan nomor tracking di atas dengan baik</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0">2</span>
                        <span>Periksa email Anda untuk notifikasi status permohonan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0">3</span>
                        <span>Lacak status permohonan kapan saja melalui halaman tracking</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0">4</span>
                        <span>Tunggu proses review dari petugas (estimasi 3-7 hari kerja)</span>
                    </li>
                </ol>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('tracking.index') }}" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 text-center inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Lacak Status Permohonan
                </a>
                <a href="{{ route('home') }}" class="flex-1 border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 text-center">
                    Kembali ke Beranda
                </a>
            </div>
            
            <!-- Register Prompt -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6 text-center">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Ingin akses lebih mudah?</h4>
                <p class="text-sm text-gray-600 mb-4">
                    Buat akun untuk melihat semua permohonan Anda dalam satu dashboard dan submit permohonan baru lebih cepat.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-700 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Buat Akun Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyTrackingNumber() {
    const trackingNumber = document.getElementById('trackingNumber').innerText.trim();
    navigator.clipboard.writeText(trackingNumber).then(function() {
        const btn = document.getElementById('copyBtnText');
        const originalText = btn.innerText;
        btn.innerText = 'Tersalin!';
        setTimeout(function() {
            btn.innerText = originalText;
        }, 2000);
    }).catch(function(err) {
        alert('Gagal menyalin: ' + err);
    });
}
</script>
@endsection
