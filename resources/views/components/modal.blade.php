{{--
Modal Component - Accessible Dialog
Usage:
<x-modal id="confirm-modal" title="Konfirmasi">
    Apakah Anda yakin?
    <x-slot name="footer">
        <x-button variant="secondary" onclick="closeModal('confirm-modal')">Batal</x-button>
        <x-button variant="danger" type="submit">Hapus</x-button>
    </x-slot>
</x-modal>

<!-- Trigger -->
<button onclick="openModal('confirm-modal')">Open Modal</button>
--}}

@props([
    'id',
    'title',
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        'full' => 'max-w-full mx-4',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div 
    id="{{ $id }}" 
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    <!-- Backdrop -->
    <div 
        class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300"
        onclick="closeModal('{{ $id }}')"
        aria-hidden="true"
    ></div>
    
    <!-- Modal Content -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div 
            class="relative w-full {{ $sizeClass }} bg-white rounded-xl shadow-xl transform transition-all duration-300 scale-95 opacity-0"
            id="{{ $id }}-content"
            role="document"
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 id="{{ $id }}-title" class="text-lg font-semibold text-gray-900">
                    {{ $title }}
                </h3>
                <button 
                    type="button" 
                    onclick="closeModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1"
                    aria-label="Tutup modal"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-4">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            @if(isset($footer))
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

@once
<script>
function openModal(id) {
    const modal = document.getElementById(id);
    const content = document.getElementById(id + '-content');
    
    if (modal && content) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Animate in
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Focus trap
        const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
        
        // Close on escape
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                closeModal(id);
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    const content = document.getElementById(id + '-content');
    
    if (modal && content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }
}
</script>
@endonce
