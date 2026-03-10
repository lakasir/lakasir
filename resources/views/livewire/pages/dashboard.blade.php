<?php

use function Livewire\Volt\layout;

layout('components.layouts.app');

?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('dashboard.welcome') }}
                </div>
            </div>
        </div>
    </div>
</div>