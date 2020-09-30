{{-- <div class="row d-flex justify-content-center"> --}}
<div class="row d-flex">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ price_format($spending->total_initial_price)  }}</h3>

        <p>{{ __('app.dashboard.total_spending') }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add"></i>
      </div>
    </div>
  </div>
</div>
