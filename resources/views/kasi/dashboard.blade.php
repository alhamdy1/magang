@extends('layouts.app')

@section('title', 'Dashboard Kasi - Sistem Perizinan Reklame')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard Kasi Perizinan</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Menunggu Review</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Sedang Direview</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['reviewing'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Disetujui Hari Ini</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_today'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Currently Reviewing -->
@if($reviewingPermits->count() > 0)
<div class="bg-white rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Sedang Direview</h2>
    </div>
    <div class="divide-y divide-gray-200">
        @foreach($reviewingPermits as $permit)
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-900">{{ $permit->nama_pemohon }}</p>
                <p class="text-sm text-gray-500">
                    {{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }} • 
                    Disetujui Operator: {{ $permit->updated_at->format('d M Y') }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('kasi.permits.show', $permit) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                    Lanjutkan Review
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Pending Permits -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">Permohonan dari Operator</h2>
        <a href="{{ route('kasi.permits.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
            Lihat Semua →
        </a>
    </div>
    <div class="divide-y divide-gray-200">
        @forelse($pendingPermits as $permit)
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-900">{{ $permit->nama_pemohon }}</p>
                <p class="text-sm text-gray-500">
                    {{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }} • 
                    Disetujui Operator: {{ $permit->updated_at->format('d M Y') }}
                </p>
            </div>
            <a href="{{ route('kasi.permits.show', $permit) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                Review
            </a>
        </div>
        @empty
        <div class="px-6 py-12 text-center">
            <p class="text-gray-500">Tidak ada permohonan yang menunggu review.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
