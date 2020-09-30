<!-- Start -->
<div class="row">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ price_format($selling->total_profit) }}</h3>

        <p>{{ __('app.dashboard.total_profit') }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag"></i>
      </div>
      <a href="{{ route('selling.index') }}" class="small-box-footer"> {{ __('app.global.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ price_format($selling->total_price)  }}</h3>

        <p>{{ __('app.dashboard.total_income') }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
      <a href="{{ route('selling.index') }}" class="small-box-footer"> {{ __('app.global.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
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
      <a href="{{ route('purchasing.index') }}" class="small-box-footer"> {{ __('app.global.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $selling->total_qty ?? 0 }}</h3>

        <p>{{ __('app.dashboard.new_orders') }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph"></i>
      </div>
      <a href="{{ route('selling.index') }}" class="small-box-footer"> {{ __('app.global.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>
<!-- End Card -->


