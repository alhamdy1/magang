{{--
Status Badge Component
Usage:
<x-status-badge status="pending" />
<x-status-badge status="approved" />
<x-status-badge status="rejected" />
--}}

@props(['status'])

@php
    $statuses = [
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu', 'icon' => '⏳'],
        'pending_operator' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Operator', 'icon' => '⏳'],
        'pending_kasi' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Menunggu Kasi', 'icon' => '📋'],
        'pending_kabid' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'label' => 'Menunggu Kabid', 'icon' => '📋'],
        'pending_admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Menunggu Admin', 'icon' => '📋'],
        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui', 'icon' => '✅'],
        'approved_kasi' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui Kasi', 'icon' => '✅'],
        'approved_kabid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui Kabid', 'icon' => '✅'],
        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak', 'icon' => '❌'],
        'rejected_kasi' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak Kasi', 'icon' => '❌'],
        'rejected_kabid' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak Kabid', 'icon' => '❌'],
        'revision' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Perlu Revisi', 'icon' => '🔄'],
        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Selesai', 'icon' => '🎉'],
        'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Dibatalkan', 'icon' => '🚫'],
        'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'Draft', 'icon' => '📝'],
    ];
    
    $config = $statuses[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($status), 'icon' => '•'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $config['bg'] . ' ' . $config['text']]) }}>
    <span class="mr-1" aria-hidden="true">{{ $config['icon'] }}</span>
    {{ $config['label'] }}
</span>
