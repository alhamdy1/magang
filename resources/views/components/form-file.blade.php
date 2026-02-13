{{-- 
Form File Upload Component - Accessible & Reusable
Usage: 
<x-form-file 
    name="dokumen" 
    label="Dokumen Pendukung" 
    accept=".pdf,.jpg,.png"
    help="Maks. 5MB, format: PDF, JPG, PNG"
/>
--}}

@props([
    'name',
    'label',
    'accept' => '.pdf,.jpg,.jpeg,.png',
    'required' => false,
    'multiple' => false,
    'help' => null,
    'error' => null,
    'maxSize' => '5MB',
])

@php
    $inputId = $name . '-' . uniqid();
    $hasError = $error ?? $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);
@endphp

<div class="mb-4">
    <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500" aria-hidden="true">*</span>
            <span class="sr-only">(wajib diisi)</span>
        @endif
    </label>
    
    <div class="relative">
        <div 
            id="{{ $inputId }}-dropzone"
            class="border-2 border-dashed rounded-lg p-6 text-center transition-colors duration-200 {{ $hasError ? 'border-red-400 bg-red-50' : 'border-gray-300 hover:border-blue-400 hover:bg-blue-50' }}"
            role="button"
            tabindex="0"
            aria-describedby="{{ $inputId }}-instructions"
            onclick="document.getElementById('{{ $inputId }}').click()"
            onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); document.getElementById('{{ $inputId }}').click(); }"
        >
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="mt-2 text-sm text-gray-600" id="{{ $inputId }}-instructions">
                <span class="font-semibold text-blue-600">Klik untuk pilih file</span>
                atau drag & drop
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Maks. {{ $maxSize }} • {{ str_replace('.', '', $accept) }}
            </p>
            <p id="{{ $inputId }}-filename" class="mt-2 text-sm text-blue-600 font-medium hidden"></p>
        </div>
        
        <input 
            type="file"
            name="{{ $name }}"
            id="{{ $inputId }}"
            accept="{{ $accept }}"
            @if($required) required aria-required="true" @endif
            @if($multiple) multiple @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
            class="sr-only"
            onchange="handleFileSelect(this, '{{ $inputId }}')"
        >
    </div>
    
    @if($help && !$hasError)
        <p id="{{ $inputId }}-help" class="mt-1 text-sm text-gray-500">
            {{ $help }}
        </p>
    @endif
    
    @if($hasError)
        <p id="{{ $inputId }}-error" class="mt-1 text-sm text-red-600" role="alert">
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $errorMessage }}
            </span>
        </p>
    @endif
</div>

<script>
function handleFileSelect(input, inputId) {
    const filenameEl = document.getElementById(inputId + '-filename');
    const dropzone = document.getElementById(inputId + '-dropzone');
    
    if (input.files && input.files.length > 0) {
        const filenames = Array.from(input.files).map(f => f.name).join(', ');
        filenameEl.textContent = '📎 ' + filenames;
        filenameEl.classList.remove('hidden');
        dropzone.classList.add('border-blue-500', 'bg-blue-50');
        dropzone.classList.remove('border-gray-300');
    } else {
        filenameEl.classList.add('hidden');
        dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        dropzone.classList.add('border-gray-300');
    }
}

// Drag and drop support
document.querySelectorAll('[id$="-dropzone"]').forEach(dropzone => {
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('border-blue-500', 'bg-blue-100');
        });
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('border-blue-500', 'bg-blue-100');
        });
    });
    
    dropzone.addEventListener('drop', (e) => {
        const inputId = dropzone.id.replace('-dropzone', '');
        const input = document.getElementById(inputId);
        if (e.dataTransfer.files.length > 0) {
            input.files = e.dataTransfer.files;
            handleFileSelect(input, inputId);
        }
    });
});
</script>
