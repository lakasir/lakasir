@if ($paid)
  <div class="row">
    <span class="badge badge-success"> {{ __('app.purchasings.paid.true') }}</span>
    <!-- Default switch -->
  </div>
@else
  <span class="badge badge-info"> {{ __('app.purchasings.paid.false') }}</span>
@endif
