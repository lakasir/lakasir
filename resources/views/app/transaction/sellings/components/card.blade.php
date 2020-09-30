<div class="row d-flex justify-content-center">
  <div class="col-lg-4 col-4">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ price_format($selling->total_profit) }}</h3>

        <p>{{ __('app.dashboard.total_profit') }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-4 col-4">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ price_format($selling->total_price)  }}</h3>

        <p>{{ __('app.dashboard.total_income') }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-4">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $selling->total_qty ?? 0 }}</h3>

        <p>{{ __('app.dashboard.new_orders') }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph"></i>
      </div>
    </div>
  </div>
</div>

