<div class="space-y-3">
  <div class="flex justify-between">
    <p>{{ __('Payment Method') }}</p>
    <p class="font-bold">{{ $cartDetail['payment_method_label'] ?? 'No Payment Method Selected' }}</p>
  </div>
  <div class="flex justify-between">
    <p>{{ __('Customer Number') }}</p>
    <p class="font-bold">{{ $cartDetail['customer_number'] ?? 'No Customer Selected' }}</p>
  </div>
  <div class="flex justify-between">
    <p>{{ __('Member') }}</p>
    <p class="font-bold">{{ $cartDetail['member_label'] ?? 'No Member Selected' }}</p>
  </div>
  <div class="flex justify-between">
    <p>{{ __('Note') }}</p>
    <div>{!! $cartDetail['note'] ?? '-' !!}</div>
  </div>
</div>

