<div class="d-flex">
  <div class="card col-md-12 p-0">
    <div class="card-header container-fluid">
      <div class="row">
        <div class="col-md-8 col-sm-12">
          <h4 class="text-muted">{{ $title }}</h4>
        </div>
        @if (isset($method) && in_array($method, ['PUT', 'PATCH']))
          <div class="col-md-4 col-sm-12">
            <div class="float-right">
              <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal"
                ><i class="far fa-money-bill-alt"></i> @lang('app.items.edit.prices_stock')</button>
            </div>
          </div>
        @endif
      </div>
    </div>
    <div class="card-body">
      {{ Builder::make('item-form')->setRoute($route, $method ?? null)->setDefaultvalue(['data' => $data ?? null])->render() }}
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="far fa-money-bill-alt"></i> @lang('app.items.edit.prices_stock')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          {{-- <p>Jika menggunakan fitur ini untuk merubah harga dan stok akan merubah log harga dan stok terakhir, tidak tercatat sebagai transaksi, jadi minta tolong pastikan lagi yaa..</p> --}}
          <p>If you use this feature to change prices and stock, it will change the last price and stock log, not recorded as a transaction, so please make sure again, please.. </p>
        </div>
        @if (isset($method) && in_array($method, ['PUT', 'PATCH']))
          {{ Builder::make('edit-price-item-form')->setRoute(route('item.update-stock-rate', $data), "PUT")->setDefaultvalue(['data' => $data ?? null])->render() }}
        @endif
      </div>
    </div>
  </div>
</div>
