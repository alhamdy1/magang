@extends('layouts.app')

@section('title', 'Selamat Datang - Sistem Perizinan Reklame')

@section('content')
<div class="text-center py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">
        Sistem Perizinan Reklame Online
    </h1>
    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
        Ajukan permohonan izin reklame secara online dengan mudah dan cepat. 
        Pantau status permohonan Anda secara real-time.
    </p>
    
    @guest
    <div class="space-x-4">
        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 inline-block">
            Daftar Sekarang
        </a>
        <a href="{{ route('login') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg text-lg font-semibold hover:bg-gray-300 inline-block">
            Masuk
        </a>
    </div>
    @else
    <a href="{{ auth()->user()->role === 'user' ? route('user.dashboard') : (auth()->user()->role === 'operator' ? route('operator.dashboard') : (auth()->user()->role === 'kasi' ? route('kasi.dashboard') : (auth()->user()->role === 'kabid' ? route('kabid.dashboard') : route('admin.dashboard')))) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 inline-block">
        Ke Dashboard
    </a>
    @endguest
</div>

<!-- Features Section -->
<div class="mt-16 grid md:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pengajuan Online</h3>
        <p class="text-gray-600">Ajukan permohonan izin reklame dari mana saja tanpa perlu datang ke kantor.</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Lokasi Presisi</h3>
        <p class="text-gray-600">Tentukan lokasi reklame dengan koordinat GPS yang akurat menggunakan peta interaktif.</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pantau Status</h3>
        <p class="text-gray-600">Lihat perkembangan permohonan Anda secara real-time dari setiap tahap proses.</p>
    </div>
</div>

<!-- Process Flow Section -->
<div class="mt-16 bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Alur Proses Perizinan</h2>
    <div class="flex flex-wrap justify-center gap-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">1</div>
            <span class="ml-2 text-gray-700">Pengajuan</span>
        </div>
        <div class="hidden md:flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold">2</div>
            <span class="ml-2 text-gray-700">Review Operator</span>
        </div>
        <div class="hidden md:flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">3</div>
            <span class="ml-2 text-gray-700">Review Kasi</span>
        </div>
        <div class="hidden md:flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">4</div>
            <span class="ml-2 text-gray-700">Review Kabid</span>
        </div>
        <div class="hidden md:flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">5</div>
            <span class="ml-2 text-gray-700">Selesai</span>
        </div>
    </div>
</div>
@endsection
