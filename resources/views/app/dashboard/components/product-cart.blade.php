<div class="card col">
  <div class="card-header border-0">
    <h3 class="card-title">{{ __('app.items.title_dashboard') }}</h3>
    <div class="card-tools">
      <a href="#" class="btn btn-tool btn-sm">
        <i class="fas fa-download"></i>
      </a>
      <a href="#" class="btn btn-tool btn-sm">
        <i class="fas fa-bars"></i>
      </a>
    </div>
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-striped table-valign-middle">
      <thead>
        <tr>
          <th>{{ __('app.items.column.name') }}</th>
          <th>{{ __('app.items.column.price.selling_price') }}</th>
          <th>{{ __('app.items.column.sales') }}</th>
          @if (app()->environment() == 'local')
            <th>{{ __('app.global.more_info') }}</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            <td>
              {{-- <img src="dist/img/default-150x150.png" alt="" class="img-circle img-size-32 mr-2"> --}}
              {{ $item->name }}
            </td>
            <td>{{ price_format($item->price_x_qty) }}</td>
            <td>
              <small class="text-success mr-1">
                <i class="fas fa-arrow-up"></i>
                {{ $item->profitLastDayPercentage }}
              </small>
              {{ $item->sellingDetails->sum('qty') }} {{ __('app.items.column.sales') }}
            </td>
            @if (app()->environment() == 'local')
              <td>
                <a href="#" class="text-muted">
                  <i class="fas fa-search"></i>
                </a>
              </td>
            @endif
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
