@extends('layouts.app')

@section('title', '429 - Terlalu Banyak Percobaan')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <div class="mb-6">
            <svg class="w-24 h-24 text-yellow-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">429</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Terlalu Banyak Percobaan</h2>
        <p class="text-gray-600 mb-6 max-w-md mx-auto">
            Anda telah melakukan terlalu banyak permintaan dalam waktu singkat. 
            Silakan tunggu <span id="countdown" class="font-bold text-blue-600">{{ $seconds ?? 60 }}</span> detik sebelum mencoba lagi.
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

<script>
    // Countdown timer
    let seconds = {{ $seconds ?? 60 }};
    const countdownEl = document.getElementById('countdown');
    
    const interval = setInterval(() => {
        seconds--;
        countdownEl.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(interval);
            location.reload();
        }
    }, 1000);
</script>
@endsection
