<div class="col-lg-6">

  <div class="card">
    <div class="card-header border-0">  <!-- /.open card header -->
      <div class="d-flex justify-content-between">
        <h3 class="card-title">Sales</h3>
        @if (app()->environment() == 'local')
          <a href="javascript:void(0);">View Report</a>
        @endif
      </div>
    </div> <!-- /.close card header -->

    <div class="card-body">
      <div class="d-flex"> <!-- /.open deflex -->
        <p class="d-flex flex-column">
        <span class="text-bold text-lg">{{ price_format($selling->total_price) }}</span>
        <span>{{ __('app.dashboard.sales_overview') }}</span>
        </p>
        <p class="ml-auto d-flex flex-column text-right">
        <span class="text-success">
          <i class="fas fa-arrow-up"></i> {{ percentage($profitMargin) }}
        </span>
        <span class="text-muted">{{ __('app.dashboard.since_last_month') }}</span>
        </p>
      </div>
      <!-- /.d-flex -->

      <div class="position-relative mb-4">
        <canvas id="sales-chart" height="200"></canvas>
      </div>

      <div class="d-flex flex-row justify-content-end">
        <span class="mr-2">
          <i class="fas fa-square text-primary"></i> {{ __('app.dashboard.this_year') }}
        </span>

        <span>
          <i class="fas fa-square text-gray"></i> {{ __('app.dashboard.last_year') }}
        </span>
      </div>
    </div> <!-- /.close card-body-->

  </div><!-- /.close card-->

</div>
