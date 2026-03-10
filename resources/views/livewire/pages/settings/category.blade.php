<?php

use App\Models\Tenants\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use function Livewire\Volt\layout;
use function Livewire\Volt\state;

layout('components.layouts.app');

state([
    'search' => '',
    'show_modal' => false,
    'modal_mode' => 'create',
    'editing_id' => null,
    'name' => '',
]);

$openCreate = function (): void {
    $this->resetErrorBag();
    $this->modal_mode = 'create';
    $this->editing_id = null;
    $this->name = '';
    $this->show_modal = true;
};

$openEdit = function (int $categoryId): void {
    $category = Category::query()->find($categoryId);

    if (! $category) {
        return;
    }

    $this->resetErrorBag();
    $this->modal_mode = 'edit';
    $this->editing_id = $category->id;
    $this->name = (string) $category->name;
    $this->show_modal = true;
};

$closeModal = function (): void {
    $this->show_modal = false;
    $this->modal_mode = 'create';
    $this->editing_id = null;
    $this->name = '';
};

$saveCategory = function (): void {
    $this->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique(Category::class, 'name')->ignore($this->editing_id),
        ],
    ]);

    try {
        if ($this->modal_mode === 'edit' && $this->editing_id) {
            Category::query()
                ->whereKey($this->editing_id)
                ->update(['name' => $this->name]);
        } else {
            Category::query()->create([
                'name' => $this->name,
            ]);
        }

        session()->flash('status', __('settings.messages.saved'));
        $this->closeModal();
    } catch (\Throwable $exception) {
        $this->addError('save', __('settings.messages.save_failed'));
    }
};

$deleteCategory = function (int $categoryId): void {
    $category = Category::query()->find($categoryId);

    if (! $category) {
        return;
    }

    if ($category->products()->exists()) {
        $this->addError('category', __('settings.category.messages.delete_blocked'));

        return;
    }

    $category->delete();
    session()->flash('status', __('settings.category.messages.deleted'));
};

?>

@php
    $settingsUrl = Route::has('settings.general') ? route('settings.general') : url('/app/settings');

    $categories = Category::query()
        ->when(filled($search), fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
        ->orderBy('name')
        ->get();
@endphp

<div class="mx-auto w-full max-w-[1000px] pb-24 pt-2">
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @error('category')
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $message }}
        </div>
    @enderror

    <header class="mb-4 flex items-center gap-4">
        <a href="{{ $settingsUrl }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-[#111111] transition hover:bg-[#efefef]" aria-label="Back">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <h1 class="text-[20px] font-semibold text-[#111111]">{{ __('settings.category.title') }}</h1>

        <div class="ml-auto flex min-w-0 flex-1 items-center gap-2">
            <div class="flex min-w-0 flex-1 items-center gap-2 rounded-full border border-[#eeeeee] bg-[#f9f9f9] px-4 py-2">
                <svg class="h-5 w-5 shrink-0 text-[#888888]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.1-4.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('settings.category.search') }}"
                    class="w-full min-w-0 border-none bg-transparent p-0 text-sm text-[#111111] placeholder:text-[#888888] focus:outline-none focus:ring-0"
                >
            </div>

            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-[#eeeeee] bg-white text-[#666666]" aria-label="Notifications">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                </svg>
            </button>
        </div>
    </header>

    <section class="space-y-2 py-2">
        @forelse($categories as $category)
            <div class="flex items-center gap-4 rounded-lg border border-[#eeeeee] bg-[#f2f2f2] px-5 py-5 md:px-8">
                <p class="min-w-0 flex-1 truncate text-base text-[#111111]">{{ $category->name }}</p>

                <button type="button" wire:click="openEdit({{ $category->id }})" class="text-[#777777] transition hover:text-[#333333]" aria-label="Edit {{ $category->name }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487a2.1 2.1 0 113.03 2.908L8.83 18.854 5 19l.22-3.72L16.862 4.487z" />
                    </svg>
                </button>

                <button
                    type="button"
                    wire:click="deleteCategory({{ $category->id }})"
                    onclick="return confirm('{{ __('settings.category.delete_confirm') }}')"
                    class="text-[#777777] transition hover:text-[#9b1c1c]"
                    aria-label="Delete {{ $category->name }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M10 11v5m4-5v5M7 7l1 12h8l1-12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2" />
                    </svg>
                </button>
            </div>
        @empty
            <div class="rounded-lg border border-dashed border-[#dddddd] bg-[#f7f7f7] px-5 py-8 text-center text-sm text-[#777777]">
                {{ __('settings.category.empty') }}
            </div>
        @endforelse
    </section>

    <div class="fixed bottom-6 right-6 z-30">
        <button type="button" wire:click="openCreate" class="inline-flex items-center gap-2 rounded-full border border-[#f60] bg-[#f60] px-6 py-3 text-sm font-semibold text-[#f9f9f9] shadow-[0_10px_30px_rgba(255,102,0,0.3)] transition hover:bg-[#e65f00]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
            </svg>
            <span>{{ __('settings.category.add_button') }}</span>
        </button>
    </div>

    @if($show_modal)
        @php
            $modalTitle = $modal_mode === 'edit'
                ? __('settings.category.edit_title')
                : __('settings.category.add_title');
        @endphp

        <div class="fixed inset-0 z-40">
            <button type="button" wire:click="closeModal" class="absolute inset-0 bg-[#0a0a0a]/80" aria-label="Close"></button>

            <div class="absolute bottom-0 left-1/2 w-full max-w-[460px] -translate-x-1/2 rounded-t-[12px] bg-white p-4 shadow-2xl md:bottom-auto md:top-1/2 md:w-[342px] md:-translate-y-1/2 md:rounded-[12px]">
                <div class="mb-5 flex items-center justify-between px-1">
                    <h2 class="text-[20px] font-semibold text-[#111111]">{{ $modalTitle }}</h2>
                    <button type="button" wire:click="closeModal" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-[#555555] transition hover:bg-[#f2f2f2]" aria-label="Close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-3">
                    <input
                        type="text"
                        wire:model="name"
                        placeholder="{{ __('settings.category.input_placeholder') }}"
                        class="w-full rounded-md border border-[#eeeeee] bg-[#f5f5f5] px-4 py-3 text-sm text-[#111111] placeholder:text-[#888888] focus:border-[#f60] focus:outline-none focus:ring-1 focus:ring-[#f60]"
                    >

                    @error('name')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @error('save')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="button" wire:click="saveCategory" class="inline-flex w-full items-center justify-center rounded-full border border-[#f60] bg-[#f60] px-4 py-3 text-sm font-semibold text-[#f9f9f9] transition hover:bg-[#e65f00]">
                        {{ __('settings.category.save') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>