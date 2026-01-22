@extends('layouts.app')

@section('title', 'Review Permohonan - Sistem Perizinan Reklame')

@push('styles')
<style>
    #map { height: 300px; width: 100%; border-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Review Permohonan</h1>
        <p class="text-gray-600">{{ $permit->nama_pemohon }}</p>
    </div>
    <div class="flex space-x-4">
        <form action="{{ route('operator.permits.release', $permit) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-600" onclick="return confirm('Apakah Anda yakin ingin melepaskan permohonan ini?')">
                Lepaskan
            </button>
        </form>
        <a href="{{ route('operator.permits.my') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300">
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Data Pemohon -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Data Pemohon</h2>
        <dl class="space-y-3">
            <div class="flex">
                <dt class="w-1/3 text-sm font-medium text-gray-500">Nama/Badan/Organisasi</dt>
                <dd class="w-2/3 text-sm text-gray-900">{{ $permit->nama_pemohon }}</dd>
            </div>
            <div class="flex">
                <dt class="w-1/3 text-sm font-medium text-gray-500">Alamat</dt>
                <dd class="w-2/3 text-sm text-gray-900">{{ $permit->alamat }}</dd>
            </div>
            <div class="flex">
                <dt class="w-1/3 text-sm font-medium text-gray-500">Nomor Telepon</dt>
                <dd class="w-2/3 text-sm text-gray-900">{{ $permit->nomor_telepon }}</dd>
            </div>
            <div class="flex">
                <dt class="w-1/3 text-sm font-medium text-gray-500">Email</dt>
                <dd class="w-2/3 text-sm text-gray-900">{{ $permit->user->email }}</dd>
            </div>
        </dl>
    </div>
    
    <!-- Data Reklame -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Data Reklame</h2>
        <dl class="space-y-3">
            <div class="flex">
                <dt class="w-1/3 text-sm font-medium text-gray-500">Klasifikasi</dt>
                <dd class="w-2/3 text-sm text-gray-900">{{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }}</dd>
            </div>
            <div class="flex">
                <dt class="w-1/3 text-sm font-medium text-gray-500">Ukuran/Jumlah</dt>
                <dd class="w-2/3 text-sm text-gray-900">{{ $permit->ukuran_jumlah }}</dd>
            </div>
            <div class="flex">
                <dt class="w-1/3 text-sm font-medium text-gray-500">Narasi</dt>
                <dd class="w-2/3 text-sm text-gray-900">{{ $permit->narasi }}</dd>
            </div>
        </dl>
    </div>
</div>

<!-- Lokasi -->
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Lokasi Reklame (Untuk Verifikasi Lapangan)</h2>
    <div class="mb-4">
        <p class="text-sm text-gray-600">
            <strong>Alamat:</strong> {{ $permit->lokasi_alamat }}
        </p>
        <p class="text-sm text-gray-500">
            <strong>Koordinat:</strong> {{ $permit->latitude }}, {{ $permit->longitude }}
            <a href="https://www.google.com/maps?q={{ $permit->latitude }},{{ $permit->longitude }}" target="_blank" class="text-blue-600 hover:text-blue-700 ml-2">
                (Buka di Google Maps)
            </a>
        </p>
    </div>
    <div id="map"></div>
</div>

<!-- Dokumen -->
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Dokumen yang Diupload</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($documentTypes as $type => $label)
            @php $document = $permit->getDocument($type); @endphp
            <div class="p-4 border rounded-lg {{ $document ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium {{ $document ? 'text-green-800' : 'text-red-800' }}">
                        {{ $label }}
                    </span>
                    @if($document)
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm">
                            Lihat
                        </a>
                    @else
                        <span class="text-red-600 text-sm">Tidak ada</span>
                    @endif
                </div>
                @if($document)
                    <p class="text-xs text-gray-500 mt-1">{{ $document->original_name }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Approval History -->
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Riwayat</h2>
    <div class="space-y-3">
        @foreach($permit->approvalHistories as $history)
        <div class="flex items-start space-x-3 text-sm">
            <div class="w-2 h-2 rounded-full mt-1.5
                @if($history->action === 'approved') bg-green-500
                @elseif($history->action === 'rejected') bg-red-500
                @else bg-blue-500
                @endif">
            </div>
            <div>
                <p class="text-gray-900">
                    <strong>{{ $history->action_label }}</strong> oleh {{ $history->user->name }} ({{ $history->level_label }})
                </p>
                <p class="text-gray-500 text-xs">{{ $history->created_at->format('d M Y H:i') }}</p>
                @if($history->notes)
                    <p class="text-gray-600 mt-1">{{ $history->notes }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Action Buttons -->
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Keputusan</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Approve Form -->
        <form action="{{ route('operator.permits.approve', $permit) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea name="notes" id="approve_notes" rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700" onclick="return confirm('Apakah Anda yakin ingin menyetujui permohonan ini?')">
                ✓ Setujui & Teruskan ke Kasi
            </button>
        </form>
        
        <!-- Reject Form -->
        <form action="{{ route('operator.permits.reject', $permit) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="notes" id="reject_notes" rows="3" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                    placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700" onclick="return confirm('Apakah Anda yakin ingin menolak permohonan ini?')">
                ✗ Tolak Permohonan
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var map = L.map('map').setView([{{ $permit->latitude }}, {{ $permit->longitude }}], 17);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([{{ $permit->latitude }}, {{ $permit->longitude }}])
        .addTo(map)
        .bindPopup('<strong>{{ $permit->nama_pemohon }}</strong><br>{{ $permit->lokasi_alamat }}')
        .openPopup();
</script>
@endpush
