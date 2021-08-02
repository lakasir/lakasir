<div class="d-flex justify-content-center">
  <div class="card col-md-8 p-0">
    <div class="card-header">
      <h4>{{ $title }}</h4>
    </div>
    <div class="card-body">
      <form action="{{ $route }}" method="POST" accept-charset="utf-8">
        @csrf
        @if ($method ?? null)
          @method($method)
        @endif
        <div class="form-group">
          <label for="unit-name">{{ __('app.customer_types.column.name') }}</label>
          <input type="text"
                 class="form-control @error('name') is-invalid @enderror"
                 name="name"
                 id="unit-name"
                 value="{{ optional($data ?? null)->name }}"
                 placeholder="{{ __('app.customer_types.placeholder.name') }}">
          @error('name')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="form-group">
          <label for="unit-default_point">{{ __('app.customer_types.column.default_point') }}</label>
          <input type="text"
                 class="form-control @error('default_point') is-invalid @enderror"
                 name="default_point"
                 id="unit-default_point"
                 value="{{ optional($data ?? null)->default_point }}"
                 placeholder="{{ __('app.customer_types.placeholder.default_point') }}">
          @error('default_point')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('app.global.submit') }}</button>
      </form>
    </div>
  </div>
</div>

