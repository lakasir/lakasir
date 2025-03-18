@php
  use App\Features\{Member, Voucher, Discount};
@endphp

<div class="space-y-3">
  @feature(Member::class)
    <div class="flex justify-between hover:underline cursor-pointer"
      x-on:mousedown="$dispatch('open-modal', {
        id: 'edit-detail',
        inputId: 'memberSelect',
        index: 0,
        title: '@lang('Search member')'
      })">
      <p>{{ __('Member') }}</p>
      <p class="font-bold">{{ $cartDetail['member_label'] ?? 'No Member Selected' }}</p>
    </div>
  @endfeature
  <div class="flex justify-between hover:underline cursor-pointer"
      x-on:mousedown="$dispatch('open-modal', {
       id: 'edit-detail',
       inputId: 'noteInput',
       index: 1,
       title: '@lang('Note')'
      })">
    <p>{{ __('Note') }}</p>
    <div>{!! $cartDetail['note'] ?? '-' !!}</div>
  </div>
  @if($about && $about->business_type == 'fnb')
    <div class="flex justify-between hover:underline cursor-pointer"
        x-on:mousedown="$dispatch('open-modal', {
          id: 'modal-selected-table'
        })">
      <p>{{ __('Table') }}</p>
      <div>{!! $cartDetail['table_id'] ?? '-' !!}</div>
    </div>
  @endif
  @feature(Voucher::class)
  <div class="flex justify-between">
    <div>
      <p>{{ __('Voucher') }}</p>
      <div class="flex gap-x-2 mt-2">
        @if($cartDetail['voucher'] == null)
          @foreach($availableVoucher as $voucher)
            <x-filament::button wire:click="assignVoucher('{{ $voucher->code }}')" color="gray">
              <x-heroicon-s-ticket class="h-6 mb-2"/>
              <p>{{ $voucher->code }}</p>
            </x-filament::button>
          @endforeach
        @endif
      </div>
    </div>
    <div class="flex items-center gap-x-2">
      {{ $cartDetail['voucher'] ?? '-' }}
      @if($cartDetail['voucher'] != null)
        <button class="py-1 px-4 bg-red-200 text-red-500 rounded-lg flex gap-x-1 items-center" wire:click="removeVoucher">
          <x-heroicon-o-x-mark class="h-4 w-4 text-red-500"/>
        </button>
      @endif
    </div>
  </div>
  @endfeature
  @feature(Discount::class)
  <div class="flex justify-between hover:underline cursor-pointer"
      x-on:mousedown="$dispatch('open-modal', {
        id: 'edit-detail',
        inputId: 'discountInput',
        index: 3,
        title: '@lang('Discount')'
      })">
    <p>{{ __('Discount') }}</p>
    <div>{{ $cartDetail['discount_price'] ?? '-' }}</div>
  </div>
  @endfeature
</div>

