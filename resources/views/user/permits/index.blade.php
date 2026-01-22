@extends('layouts.app')

@section('title', 'Daftar Permohonan - Sistem Perizinan Reklame')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Permohonan Saya</h1>
        <p class="text-gray-600">Kelola semua permohonan izin reklame Anda</p>
    </div>
    <a href="{{ route('user.permits.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Buat Permohonan
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemohon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klasifikasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Ajukan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($permits as $index => $permit)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permits->firstItem() + $index }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $permit->nama_pemohon }}</div>
                    <div class="text-sm text-gray-500">{{ $permit->permit_number ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permit->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($permit->status_color === 'green') bg-green-100 text-green-800
                        @elseif($permit->status_color === 'yellow') bg-yellow-100 text-yellow-800
                        @elseif($permit->status_color === 'red') bg-red-100 text-red-800
                        @elseif($permit->status_color === 'blue') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $permit->status_label }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('user.permits.show', $permit) }}" class="text-blue-600 hover:text-blue-700">Detail</a>
                    <a href="{{ route('user.permits.track', $permit) }}" class="text-green-600 hover:text-green-700">Lacak</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    Belum ada permohonan. <a href="{{ route('user.permits.create') }}" class="text-blue-600 hover:text-blue-700">Buat permohonan pertama Anda</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($permits->hasPages())
<div class="mt-4">
    {{ $permits->links() }}
</div>
@endif
@endsection
