@extends('layouts.app')

@section('title', 'Lacak Permohonan - Sistem Perizinan Reklame')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Lacak Permohonan</h1>
        <p class="text-gray-600">{{ $permit->nama_pemohon }}</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('user.permits.show', $permit) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">
            Lihat Detail
        </a>
        <a href="{{ route('user.permits.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300">
            Kembali
        </a>
    </div>
</div>

<!-- Current Status -->
<div class="mb-8 p-6 rounded-lg bg-white shadow">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Saat Ini</h2>
    <div class="flex items-center">
        <span class="px-4 py-2 rounded-full text-lg font-medium
            @if($permit->status_color === 'green') bg-green-100 text-green-800
            @elseif($permit->status_color === 'yellow') bg-yellow-100 text-yellow-800
            @elseif($permit->status_color === 'red') bg-red-100 text-red-800
            @elseif($permit->status_color === 'blue') bg-blue-100 text-blue-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ $permit->status_label }}
        </span>
        @if($permit->permit_number)
        <span class="ml-4 text-lg font-bold text-green-700">
            Nomor Izin: {{ $permit->permit_number }}
        </span>
        @endif
    </div>
</div>

<!-- Progress Steps -->
<div class="mb-8 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-6">Tahapan Proses</h2>
    
    @php
        $stages = [
            ['status' => 'submitted', 'label' => 'Pengajuan', 'icon' => '📝'],
            ['status' => 'operator', 'label' => 'Review Operator', 'icon' => '👤'],
            ['status' => 'kasi', 'label' => 'Review Kasi Perizinan', 'icon' => '👨‍💼'],
            ['status' => 'kabid', 'label' => 'Review Kabid Penyelenggaraan', 'icon' => '🏢'],
            ['status' => 'completed', 'label' => 'Selesai', 'icon' => '✅'],
        ];
        
        $currentStage = 0;
        if (in_array($permit->status, ['submitted'])) $currentStage = 1;
        if (in_array($permit->status, ['operator_review', 'operator_approved', 'operator_rejected'])) $currentStage = 2;
        if (in_array($permit->status, ['kasi_review', 'kasi_approved', 'kasi_rejected'])) $currentStage = 3;
        if (in_array($permit->status, ['kabid_review', 'kabid_approved', 'kabid_rejected'])) $currentStage = 4;
        if ($permit->status === 'completed') $currentStage = 5;
        
        $isRejected = in_array($permit->status, ['operator_rejected', 'kasi_rejected', 'kabid_rejected']);
    @endphp
    
    <div class="flex items-center justify-between">
        @foreach($stages as $index => $stage)
            @php
                $stageNumber = $index + 1;
                $isCompleted = $stageNumber < $currentStage;
                $isCurrent = $stageNumber == $currentStage;
                $isUpcoming = $stageNumber > $currentStage;
            @endphp
            
            <div class="flex flex-col items-center {{ $index < count($stages) - 1 ? 'flex-1' : '' }}">
                <div class="relative flex items-center justify-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl
                        @if($isCompleted) bg-green-500 text-white
                        @elseif($isCurrent && $isRejected) bg-red-500 text-white
                        @elseif($isCurrent) bg-blue-500 text-white
                        @else bg-gray-200 text-gray-500
                        @endif">
                        @if($isCompleted)
                            ✓
                        @elseif($isCurrent && $isRejected)
                            ✗
                        @else
                            {{ $stage['icon'] }}
                        @endif
                    </div>
                </div>
                <p class="text-xs text-center mt-2 
                    @if($isCurrent) font-semibold text-blue-600
                    @elseif($isCompleted) text-green-600
                    @else text-gray-500
                    @endif">
                    {{ $stage['label'] }}
                </p>
            </div>
            
            @if($index < count($stages) - 1)
            <div class="flex-1 h-1 mx-2
                @if($stageNumber < $currentStage) bg-green-500
                @elseif($stageNumber == $currentStage && $isRejected) bg-red-500
                @elseif($stageNumber == $currentStage) bg-blue-500
                @else bg-gray-200
                @endif">
            </div>
            @endif
        @endforeach
    </div>
</div>

<!-- Timeline History -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-6">Riwayat Permohonan</h2>
    
    <div class="relative">
        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
        
        <div class="space-y-6">
            @foreach($histories as $history)
            <div class="relative pl-10">
                <div class="absolute left-2 w-5 h-5 rounded-full 
                    @if($history->action === 'approved') bg-green-500
                    @elseif($history->action === 'rejected') bg-red-500
                    @elseif($history->action === 'claimed') bg-yellow-500
                    @else bg-blue-500
                    @endif">
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $history->action_label }}
                                <span class="text-sm font-normal text-gray-500">oleh {{ $history->level_label }}</span>
                            </p>
                            <p class="text-sm text-gray-600">{{ $history->user->name }}</p>
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ $history->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                    @if($history->notes)
                    <p class="mt-2 text-sm text-gray-700 bg-white p-2 rounded border">
                        {{ $history->notes }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
