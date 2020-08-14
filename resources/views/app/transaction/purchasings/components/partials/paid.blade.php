@if ($paid)
  <span class="badge badge-success"> {{ __('app.purchasings.paid.true') }}</span>
@else
  <span class="badge badge-info"> {{ __('app.purchasings.paid.false') }}</span>
@endif
