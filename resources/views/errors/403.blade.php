@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <div class="mb-6">
            <svg class="w-24 h-24 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h1 class="text-6xl font-bold text-gray-900 mb-4">403</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Akses Ditolak</h2>
        <p class="text-gray-600 mb-6 max-w-md mx-auto">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>
        <div class="space-x-4">
            <a href="{{ url()->previous() }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
                Kembali
            </a>
            <a href="{{ route('home') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 inline-block">
                Beranda
            </a>
        </div>
    </div>
</div>
@endsection
