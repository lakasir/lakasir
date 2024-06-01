<div class="space-y-3">
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Payment Method') }}</p>
    <p class="font-bold">{{ $cartDetail['payment_method_label'] ?? 'No Payment Method Selected' }}</p>
  </div>
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Customer Number') }}</p>
    <p class="font-bold">{{ $cartDetail['customer_number'] ?? 'No Customer Selected' }}</p>
  </div>
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Member') }}</p>
    <p class="font-bold">{{ $cartDetail['member_label'] ?? 'No Member Selected' }}</p>
  </div>
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Note') }}</p>
    <div>{!! $cartDetail['note'] ?? '-' !!}</div>
  </div>
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Voucher') }}</p>
    <div>{{ $cartDetail['voucher'] ?? '-' }}</div>
  </div>
  <div class="flex justify-between hover:underline cursor-pointer"
    x-on:click="$dispatch('open-modal', {id: 'edit-detail'})" >
    <p>{{ __('Discount') }}</p>
    <div>{{ $cartDetail['discount_price'] ?? '-' }}</div>
  </div>
</div>

