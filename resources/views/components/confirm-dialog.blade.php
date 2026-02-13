{{--
Confirm Dialog Component - For delete and dangerous actions
Usage:
<x-confirm-dialog 
    id="delete-confirm"
    title="Hapus Permohonan"
    message="Apakah Anda yakin ingin menghapus permohonan ini? Tindakan ini tidak dapat dibatalkan."
    confirmText="Ya, Hapus"
    cancelText="Batal"
    variant="danger"
    :formAction="route('permits.destroy', $permit)"
    formMethod="DELETE"
/>

<!-- Trigger -->
<button onclick="openConfirmDialog('delete-confirm')">Hapus</button>
--}}

@props([
    'id',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin ingin melakukan tindakan ini?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'variant' => 'danger', // danger, warning, info
    'formAction' => null,
    'formMethod' => 'POST',
])

@php
    $variants = [
        'danger' => [
            'icon_bg' => 'bg-red-100',
            'icon_color' => 'text-red-600',
            'button_bg' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        ],
        'warning' => [
            'icon_bg' => 'bg-yellow-100',
            'icon_color' => 'text-yellow-600',
            'button_bg' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        ],
        'info' => [
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-600',
            'button_bg' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        ],
    ];
    $config = $variants[$variant] ?? $variants['danger'];
@endphp

<div 
    id="{{ $id }}" 
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    role="alertdialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    aria-describedby="{{ $id }}-description"
>
    <!-- Backdrop -->
    <div 
        class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300"
        onclick="closeConfirmDialog('{{ $id }}')"
    ></div>
    
    <!-- Dialog Content -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div 
            class="relative w-full max-w-md bg-white rounded-xl shadow-xl transform transition-all duration-300 scale-95 opacity-0"
            id="{{ $id }}-content"
        >
            <div class="p-6">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full {{ $config['icon_bg'] }} mb-4">
                    @if($variant === 'danger')
                        <svg class="h-6 w-6 {{ $config['icon_color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @elseif($variant === 'warning')
                        <svg class="h-6 w-6 {{ $config['icon_color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @else
                        <svg class="h-6 w-6 {{ $config['icon_color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>
                
                <!-- Title -->
                <h3 id="{{ $id }}-title" class="text-lg font-medium text-gray-900 text-center">
                    {{ $title }}
                </h3>
                
                <!-- Message -->
                <p id="{{ $id }}-description" class="mt-2 text-sm text-gray-500 text-center">
                    {{ $message }}
                </p>
            </div>
            
            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button 
                    type="button"
                    onclick="closeConfirmDialog('{{ $id }}')"
                    class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                    {{ $cancelText }}
                </button>
                
                @if($formAction)
                    <form action="{{ $formAction }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @if($formMethod !== 'POST')
                            @method($formMethod)
                        @endif
                        <button 
                            type="submit"
                            class="w-full px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $config['button_bg'] }}"
                        >
                            {{ $confirmText }}
                        </button>
                    </form>
                @else
                    <button 
                        type="button"
                        id="{{ $id }}-confirm-btn"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $config['button_bg'] }}"
                    >
                        {{ $confirmText }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@once
<script>
function openConfirmDialog(id, callback) {
    const dialog = document.getElementById(id);
    const content = document.getElementById(id + '-content');
    
    if (dialog && content) {
        dialog.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Store callback if provided
        if (callback) {
            const confirmBtn = document.getElementById(id + '-confirm-btn');
            if (confirmBtn) {
                confirmBtn.onclick = () => {
                    callback();
                    closeConfirmDialog(id);
                };
            }
        }
        
        // Close on escape
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                closeConfirmDialog(id);
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
    }
}

function closeConfirmDialog(id) {
    const dialog = document.getElementById(id);
    const content = document.getElementById(id + '-content');
    
    if (dialog && content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            dialog.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }
}
</script>
@endonce
