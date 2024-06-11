@php
  use App\Features\{Member, Voucher};
@endphp

<div class="space-y-3">
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Payment Method') }}</p>
    <p class="font-bold">{{ $cartDetail['payment_method_label'] ?? 'No Payment Method Selected' }}</p>
  </div>
  @feature(Member::class)
    <div class="flex justify-between hover:underline cursor-pointer"
      x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
      <p>{{ __('Member') }}</p>
      <p class="font-bold">{{ $cartDetail['member_label'] ?? 'No Member Selected' }}</p>
    </div>
  @endfeature
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Note') }}</p>
    <div>{!! $cartDetail['note'] ?? '-' !!}</div>
  </div>
  @feature(Voucher::class)
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Voucher') }}</p>
    <div>{{ $cartDetail['voucher'] ?? '-' }}</div>
  </div>
  @endfeature
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Discount') }}</p>
    <div>{{ $cartDetail['discount_price'] ?? '-' }}</div>
  </div>
</div>

