@extends('layouts.app')

@section('title', '500 - Kesalahan Server')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <div class="mb-6">
            <svg class="w-24 h-24 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-6xl font-bold text-gray-900 mb-4">500</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Kesalahan Server</h2>
        <p class="text-gray-600 mb-6 max-w-md mx-auto">
            Maaf, terjadi kesalahan pada server kami. Tim teknis kami telah diberitahu dan sedang bekerja untuk memperbaikinya.
        </p>
        <div class="space-x-4">
            <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
                Kembali ke Beranda
            </a>
            <button onclick="location.reload()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 inline-block">
                Coba Lagi
            </button>
        </div>
    </div>
</div>
@endsection
