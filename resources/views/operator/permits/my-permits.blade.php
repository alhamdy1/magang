@extends('layouts.app')

@section('title', 'Review Saya - Sistem Perizinan Reklame')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Permohonan yang Saya Ambil</h1>
    <p class="text-gray-600">Daftar permohonan yang sedang Anda review</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemohon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klasifikasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diambil Pada</th>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permit->claimed_at->format('d M Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('operator.permits.show', $permit) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                        Review
                    </a>
                    <form action="{{ route('operator.permits.release', $permit) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600" onclick="return confirm('Apakah Anda yakin ingin melepaskan permohonan ini?')">
                            Lepas
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    Anda belum mengambil permohonan apapun. <a href="{{ route('operator.permits.index') }}" class="text-blue-600 hover:text-blue-700">Ambil permohonan</a>
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
