@extends('layouts.app')

@section('title', 'Daftar Permohonan - Kasi Perijinan')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Permohonan untuk Review</h1>
    <p class="text-gray-600">Permohonan yang sudah disetujui oleh operator</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemohon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klasifikasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disetujui Operator</th>
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
                    <div class="text-sm text-gray-500">{{ $permit->nomor_telepon }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ Str::limit($permit->lokasi_alamat, 30) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permit->updated_at->format('d M Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('kasi.permits.show', $permit) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                        Review
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    Tidak ada permohonan yang menunggu review.
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
