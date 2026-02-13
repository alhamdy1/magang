{{-- 
Form Input Component - Accessible & Reusable
Usage: 
<x-form-input 
    name="email" 
    label="Email" 
    type="email" 
    required 
    placeholder="contoh@email.com"
    help="Masukkan email yang valid"
/>
--}}

@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => null,
    'error' => null,
    'maxlength' => null,
    'minlength' => null,
    'pattern' => null,
    'autocomplete' => null,
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
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $inputId }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required aria-required="true" @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($minlength) minlength="{{ $minlength }}" @endif
        @if($pattern) pattern="{{ $pattern }}" @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($hasError) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
        @if($help && !$hasError) aria-describedby="{{ $inputId }}-help" @endif
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2 border rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ' . 
                ($hasError ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-gray-400')
        ]) }}
    >
    
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
