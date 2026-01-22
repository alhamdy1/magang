@extends('layouts.app')

@section('title', 'Buat Permohonan Izin - Sistem Perizinan Reklame')

@push('styles')
<style>
    #map { height: 400px; width: 100%; border-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Buat Permohonan Izin Reklame</h1>
    <p class="text-gray-600">Lengkapi formulir di bawah ini untuk mengajukan permohonan izin reklame</p>
</div>

<form method="POST" action="{{ route('user.permits.store') }}" enctype="multipart/form-data" class="space-y-8">
    @csrf
    
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
                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nomor_telepon') border-red-500 @enderror">
                @error('nomor_telepon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
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
        <p class="text-sm text-gray-500 mb-4">Upload dokumen dalam format JPG, JPEG, PNG, atau PDF. Ukuran maksimal 5MB per file (10MB untuk foto kondisi dan gambar konstruksi).</p>
        
        <div class="space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <p class="text-sm text-blue-800">
                    <strong>Template Surat:</strong> Download template surat yang diperlukan:
                    <br>
                    • <a href="#" class="underline">Template Surat Permohonan Izin</a>
                    <br>
                    • <a href="#" class="underline">Template Surat Pernyataan Pertanggung Jawaban Konstruksi</a>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($documentTypes as $type => $label)
                <div>
                    <label for="{{ $type }}" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $label }} 
                        @if($type !== 'surat_kuasa')
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input type="file" name="{{ $type }}" id="{{ $type }}" 
                        {{ $type !== 'surat_kuasa' ? 'required' : '' }}
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error($type) border-red-500 @enderror">
                    @error($type)
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Submit Button -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('user.permits.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
            Ajukan Permohonan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
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
