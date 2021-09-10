<div class="d-flex">
  <div class="card col-md-8 p-0">
    <div class="card-header">
      <h4>{{ $title }}</h4>
    </div>
    <div class="card-body">
      {{ Builder::make('user-form')->setRoute($route, $method ?? null)->setDefaultvalue(['data' => $data ?? null])->render() }}
    </div>
  </div>
</div>
