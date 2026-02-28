@props([
    'title' => 'Are you sure?',
    'message' => null,
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'variant' => 'danger',
])

<x-ui.modal 
    :title="$title"
    wire:model="confirming"
    size="sm"
>
    <p class="text-gray-600 dark:text-gray-400">{{ $message }}</p>
    
    <x-slot:actions>
        <x-ui.button variant="ghost" @click="$dispatch('close')">
            {{ $cancelText }}
        </x-ui.button>
        <x-ui.button :variant="$variant" wire:click="confirm">
            {{ $confirmText }}
        </x-ui.button>
    </x-slot:actions>
</x-ui.modal>