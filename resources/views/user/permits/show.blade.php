@extends('layouts.app')

@section('title', 'Detail Permohonan - Sistem Perizinan Reklame')

@push('styles')
<style>
    #map { height: 300px; width: 100%; border-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Permohonan</h1>
        <p class="text-gray-600">{{ $permit->nama_pemohon }}</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('user.permits.track', $permit) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700">
            Lacak Status
        </a>
        <a href="{{ route('user.permits.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300">
            Kembali
        </a>
    </div>
</div>

<!-- Status Banner -->
<div class="mb-6 p-4 rounded-lg 
    @if($permit->status_color === 'green') bg-green-100 border border-green-400
    @elseif($permit->status_color === 'yellow') bg-yellow-100 border border-yellow-400
    @elseif($permit->status_color === 'red') bg-red-100 border border-red-400
    @elseif($permit->status_color === 'blue') bg-blue-100 border border-blue-400
    @else bg-gray-100 border border-gray-400
    @endif">
    <div class="flex items-center justify-between">
        <div>
            <span class="font-semibold">Status:</span>
            <span class="ml-2">{{ $permit->status_label }}</span>
        </div>
        @if($permit->permit_number)
        <div>
            <span class="font-semibold">Nomor Izin:</span>
            <span class="ml-2 text-lg font-bold text-green-700">{{ $permit->permit_number }}</span>
        </div>
        @endif
    </div>
    @if($permit->status === 'completed')
    <p class="mt-2 text-sm">
        <strong>Selamat!</strong> Permohonan Anda telah disetujui. Silahkan datang ke kantor untuk mengambil dokumen keputusan.
    </p>
    @endif
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
    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Lokasi Reklame</h2>
    <div class="mb-4">
        <p class="text-sm text-gray-600">
            <strong>Alamat:</strong> {{ $permit->lokasi_alamat }}
        </p>
        <p class="text-sm text-gray-500">
            <strong>Koordinat:</strong> {{ $permit->latitude }}, {{ $permit->longitude }}
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
            <div class="p-4 border rounded-lg {{ $document ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium {{ $document ? 'text-green-800' : 'text-gray-500' }}">
                        {{ $label }}
                    </span>
                    @if($document)
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm">
                            Lihat
                        </a>
                    @else
                        <span class="text-gray-400 text-sm">-</span>
                    @endif
                </div>
                @if($document)
                    <p class="text-xs text-gray-500 mt-1">{{ $document->original_name }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Catatan dari Reviewer -->
@if($permit->operator_notes || $permit->kasi_notes || $permit->kabid_notes)
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Catatan dari Reviewer</h2>
    <div class="space-y-4">
        @if($permit->operator_notes)
        <div class="p-4 bg-yellow-50 rounded-lg">
            <p class="text-sm font-medium text-yellow-800">Catatan Operator:</p>
            <p class="text-sm text-gray-700 mt-1">{{ $permit->operator_notes }}</p>
        </div>
        @endif
        @if($permit->kasi_notes)
        <div class="p-4 bg-orange-50 rounded-lg">
            <p class="text-sm font-medium text-orange-800">Catatan Kasi Perijinan:</p>
            <p class="text-sm text-gray-700 mt-1">{{ $permit->kasi_notes }}</p>
        </div>
        @endif
        @if($permit->kabid_notes)
        <div class="p-4 bg-purple-50 rounded-lg">
            <p class="text-sm font-medium text-purple-800">Catatan Kabid Penyelenggaraan:</p>
            <p class="text-sm text-gray-700 mt-1">{{ $permit->kabid_notes }}</p>
        </div>
        @endif
    </div>
</div>
@endif
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
