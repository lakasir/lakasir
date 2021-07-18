<div class="d-flex justify-content-center">
  <div class="card col-md-8 p-0">
    <div class="card-header">
      <h4>{{ $title }}</h4>
    </div>
    <div class="card-body">
      {{ Builder::make('customer-form')
        ->setRoute($route, $method ?? null)
        ->setDefaultvalue(['data' => $data ?? null])
        ->render() }}
    </div>
  </div>
</div>
