@props([
    'options' => [],
    'placeholder' => 'Select an option',
    'multiple' => false,
    'disabled' => false,
])

@php
    $inputId = $attributes->get('id', 'select-' . Str::random(8));
@endphp

<div class="w-full">
    @if($attributes->get('label'))
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $attributes->get('label') }}
        </label>
    @endif
    
    <select 
        id="{{ $inputId }}"
        {{ $attributes->except('label')->merge([
            'class' => 'w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-lakasir-primary focus:border-transparent disabled:bg-gray-100 disabled:dark:bg-gray-800 disabled:cursor-not-allowed disabled:text-gray-500'
        ]) }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $disabled ? 'disabled' : '' }}
    >
        @if($placeholder && !$multiple)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
    
    @if($attributes->get('error'))
        <p class="mt-1 text-sm text-red-500">{{ $attributes->get('error') }}</p>
    @endif
</div>