@extends('layouts.app')

@section('title', 'Lacak Permohonan - Sistem Perizinan Reklame')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Lacak Status Permohonan</h1>
        <p class="text-gray-600 mt-2">Masukkan data untuk melacak status permohonan izin reklame Anda</p>
    </div>
    
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="POST" action="{{ route('tracking.search') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Tracking Number (Optional) -->
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Tracking 
                        <span class="text-gray-400">(Opsional - kosongkan untuk melihat semua permohonan)</span>
                    </label>
                    <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number') }}"
                        placeholder="Contoh: TRK-20260124-ABC123"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tracking_number') border-red-500 @enderror">
                    @error('tracking_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Jika tidak ingat nomor tracking, kosongkan saja. Sistem akan menampilkan semua permohonan yang sesuai dengan email dan NIK.</p>
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        placeholder="Email yang digunakan saat pengajuan"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                        NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                        maxlength="16" pattern="[0-9]{16}"
                        placeholder="16 digit NIK"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik') border-red-500 @enderror">
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Lacak Permohonan
                </button>
            </div>
        </form>
    </div>
    
    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-semibold text-blue-900 mb-2">Informasi</h3>
        <ul class="text-sm text-blue-800 space-y-2">
            <li class="flex items-start">
                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Email dan NIK harus sesuai dengan data yang digunakan saat pengajuan
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Jika tidak ingat nomor tracking, masukkan email dan NIK untuk melihat semua permohonan Anda
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Notifikasi status juga dikirim ke email yang terdaftar
            </li>
        </ul>
    </div>
    
    <!-- Quick Links -->
    <div class="mt-6 text-center text-sm text-gray-600">
        <p>Belum pernah mengajukan permohonan?</p>
        <a href="{{ route('guest.permits.create') }}" class="text-blue-600 font-medium hover:text-blue-700">
            Ajukan Permohonan Baru →
        </a>
    </div>
</div>
@endsection
