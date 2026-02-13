@extends('layouts.app')

@section('title', 'Verifikasi - Lacak Permohonan')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Verifikasi Identitas</h1>
        <p class="text-gray-600 mt-2">Untuk keamanan, silakan verifikasi email dan NIK Anda</p>
    </div>
    
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600">Nomor Tracking:</p>
            <p class="font-mono text-lg font-bold text-gray-900">{{ $trackingNumber }}</p>
        </div>
        
        <form method="POST" action="{{ route('tracking.verify', ['trackingNumber' => $trackingNumber]) }}">
            @csrf
            
            <div class="space-y-4">
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
                
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                        maxlength="16" pattern="[0-9]{16}"
                        placeholder="16 digit NIK"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik') border-red-500 @enderror">
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Verifikasi & Lihat Status
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-6 text-center">
        <a href="{{ route('tracking.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
            ← Kembali ke halaman tracking
        </a>
    </div>
</div>
@endsection
