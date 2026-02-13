@extends('layouts.app')

@section('title', 'Daftar Permohonan - Sistem Perizinan Reklame')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Permohonan Anda</h1>
        <p class="text-gray-600 mt-2">
            Ditemukan <strong>{{ $permits->count() }}</strong> permohonan untuk email <strong>{{ $email }}</strong>
        </p>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nomor Tracking
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Pemohon
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($permits as $permit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-blue-600 font-medium">{{ $permit->tracking_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $permit->nama_pemohon }}</div>
                            <div class="text-sm text-gray-500">{{ $permit->klasifikasi === 'permanen' ? 'Permanen' : 'Non Permanen' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $permit->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($permit->status_color === 'green') bg-green-100 text-green-800
                                @elseif($permit->status_color === 'red') bg-red-100 text-red-800
                                @elseif($permit->status_color === 'yellow') bg-yellow-100 text-yellow-800
                                @elseif($permit->status_color === 'blue') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $permit->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('tracking.show', ['trackingNumber' => $permit->tracking_number]) }}" 
                               class="text-blue-600 hover:text-blue-900 font-medium"
                               onclick="sessionStorage.setItem('verified_email', '{{ $email }}'); sessionStorage.setItem('verified_nik', '{{ $nik }}');">
                                Lihat Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('tracking.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Cari Permohonan Lain
        </a>
        <a href="{{ route('guest.permits.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">
            Ajukan Permohonan Baru
        </a>
    </div>
</div>
@endsection
