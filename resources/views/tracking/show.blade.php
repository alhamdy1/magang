@extends('layouts.app')

@section('title', 'Detail Permohonan ' . $permit->tracking_number . ' - Sistem Perizinan Reklame')

@push('styles')
<style>
    #map { height: 300px; width: 100%; border-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('tracking.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
            ← Kembali ke Tracking
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Permohonan</h1>
                <p class="text-gray-500 font-mono">{{ $permit->tracking_number }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                    @if($permit->status_color === 'green') bg-green-100 text-green-800
                    @elseif($permit->status_color === 'red') bg-red-100 text-red-800
                    @elseif($permit->status_color === 'yellow') bg-yellow-100 text-yellow-800
                    @elseif($permit->status_color === 'blue') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ $permit->status_label }}
                </span>
            </div>
        </div>
    </div>
    
    @if($permit->permit_number)
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-green-900">Permohonan Disetujui!</h3>
                <p class="text-green-700">Nomor Izin: <span class="font-mono font-bold">{{ $permit->permit_number }}</span></p>
                <p class="text-sm text-green-600 mt-1">Silahkan datang ke kantor untuk mengambil dokumen izin Anda.</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Progress Timeline -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Progress Permohonan</h2>
        
        <div class="relative">
            @php
                $stages = [
                    ['key' => 'submitted', 'label' => 'Diajukan', 'description' => 'Permohonan diterima'],
                    ['key' => 'operator', 'label' => 'Review Operator', 'description' => 'Pemeriksaan dokumen'],
                    ['key' => 'kasi', 'label' => 'Review Kasi', 'description' => 'Verifikasi supervisor'],
                    ['key' => 'kabid', 'label' => 'Review Kabid', 'description' => 'Persetujuan akhir'],
                    ['key' => 'completed', 'label' => 'Selesai', 'description' => 'Izin diterbitkan'],
                ];
                
                $currentIndex = match(true) {
                    str_contains($permit->status, 'rejected') => -1,
                    $permit->status === 'completed' => 5,
                    str_contains($permit->status, 'kabid') => 4,
                    str_contains($permit->status, 'kasi') => 3,
                    str_contains($permit->status, 'operator') => 2,
                    $permit->status === 'submitted' => 1,
                    default => 0,
                };
                
                $isRejected = str_contains($permit->status, 'rejected');
            @endphp
            
            @if($isRejected)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-red-800">Permohonan Ditolak</p>
                        <p class="text-sm text-red-600">{{ $permit->status_label }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="flex justify-between">
                @foreach($stages as $index => $stage)
                <div class="flex flex-col items-center relative flex-1">
                    @if($index > 0)
                    <div class="absolute left-0 right-1/2 top-4 h-0.5 -translate-y-1/2
                        @if($index < $currentIndex) bg-green-500
                        @elseif($isRejected && $index <= $currentIndex + 1) bg-red-500
                        @else bg-gray-200 @endif"></div>
                    @endif
                    @if($index < count($stages) - 1)
                    <div class="absolute left-1/2 right-0 top-4 h-0.5 -translate-y-1/2
                        @if($index < $currentIndex - 1) bg-green-500
                        @elseif($isRejected && $index < $currentIndex + 1) bg-red-500
                        @else bg-gray-200 @endif"></div>
                    @endif
                    
                    <div class="w-8 h-8 rounded-full flex items-center justify-center z-10
                        @if($index < $currentIndex) bg-green-500 text-white
                        @elseif($index === $currentIndex && !$isRejected) bg-blue-500 text-white
                        @elseif($isRejected && $index === $currentIndex + 1) bg-red-500 text-white
                        @else bg-gray-200 text-gray-500 @endif">
                        @if($index < $currentIndex)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($isRejected && $index === $currentIndex + 1)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @else
                            <span class="text-xs font-bold">{{ $index + 1 }}</span>
                        @endif
                    </div>
                    <span class="text-xs font-medium mt-2 text-center
                        @if($index < $currentIndex) text-green-600
                        @elseif($index === $currentIndex && !$isRejected) text-blue-600
                        @elseif($isRejected && $index === $currentIndex + 1) text-red-600
                        @else text-gray-400 @endif">
                        {{ $stage['label'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Data Pemohon -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Data Pemohon</h2>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Nama</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $permit->nama_pemohon }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">NIK</dt>
                    <dd class="text-sm font-medium text-gray-900 font-mono">{{ $permit->nik_pemohon ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Telepon</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $permit->nomor_telepon }}</dd>
                </div>
                <div class="flex flex-col">
                    <dt class="text-sm text-gray-500">Alamat</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $permit->alamat }}</dd>
                </div>
            </dl>
        </div>
        
        <!-- Data Reklame -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Data Reklame</h2>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Klasifikasi</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Ukuran/Jumlah</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $permit->ukuran_jumlah }}</dd>
                </div>
                <div class="flex flex-col">
                    <dt class="text-sm text-gray-500">Narasi</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $permit->narasi }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Lokasi -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Lokasi Reklame</h2>
        <p class="text-sm text-gray-600 mb-4">{{ $permit->lokasi_alamat }}</p>
        <div id="map"></div>
        <div class="mt-2 text-xs text-gray-500">
            Koordinat: {{ $permit->latitude }}, {{ $permit->longitude }}
        </div>
    </div>
    
    <!-- Approval History -->
    @if($permit->approvalHistories->count() > 0)
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Riwayat Persetujuan</h2>
        <div class="space-y-4">
            @foreach($permit->approvalHistories->sortByDesc('created_at') as $history)
            <div class="flex items-start">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                    @if($history->action === 'approve' || $history->action === 'approved') bg-green-100
                    @elseif($history->action === 'reject' || $history->action === 'rejected') bg-red-100
                    @else bg-blue-100 @endif">
                    @if($history->action === 'approve' || $history->action === 'approved')
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @elseif($history->action === 'reject' || $history->action === 'rejected')
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">{{ $history->user->name ?? 'System' }}</p>
                        <span class="text-xs text-gray-500">{{ $history->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        {{ ucfirst($history->action) }} - {{ $history->from_status }} → {{ $history->to_status }}
                    </p>
                    @if($history->notes)
                    <p class="text-sm text-gray-500 mt-1 italic">"{{ $history->notes }}"</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Notes from reviewers -->
    @if($permit->operator_notes || $permit->kasi_notes || $permit->kabid_notes)
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Catatan Petugas</h2>
        <div class="space-y-4">
            @if($permit->operator_notes)
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-700">Catatan Operator:</p>
                <p class="text-sm text-gray-600 mt-1">{{ $permit->operator_notes }}</p>
            </div>
            @endif
            @if($permit->kasi_notes)
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-700">Catatan Kasi:</p>
                <p class="text-sm text-gray-600 mt-1">{{ $permit->kasi_notes }}</p>
            </div>
            @endif
            @if($permit->kabid_notes)
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-700">Catatan Kabid:</p>
                <p class="text-sm text-gray-600 mt-1">{{ $permit->kabid_notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Timestamps -->
    <div class="text-sm text-gray-500 mt-6 text-center">
        Diajukan pada {{ $permit->created_at->format('d M Y, H:i') }} WIB
        @if($permit->updated_at->gt($permit->created_at))
        | Terakhir diperbarui {{ $permit->updated_at->format('d M Y, H:i') }} WIB
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([{{ $permit->latitude }}, {{ $permit->longitude }}], 16);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([{{ $permit->latitude }}, {{ $permit->longitude }}])
        .addTo(map)
        .bindPopup('<strong>Lokasi Reklame</strong><br>{{ $permit->lokasi_alamat }}')
        .openPopup();
</script>
@endpush
