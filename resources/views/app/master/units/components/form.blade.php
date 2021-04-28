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
          <label for="unit-name">{{ __('app.units.column.name') }}</label>
          <input type="text"
                 class="form-control @error('name') is-invalid @enderror"
                 name="name"
                 id="unit-name"
                 value="{{ optional($data ?? null)->name }}"
                 placeholder="{{ __('app.units.placeholder.name') }}">
          @error('name')
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
