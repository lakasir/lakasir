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
        <label for="unit-name">{{ __('app.groups.column.name') }}</label>
        <input type="text"
        class="form-control @error('name') is-invalid @enderror"
        name="name"
        value="{{ optional($data ?? null)->name }}"
        placeholder="{{ __('app.groups.placeholder.name') }}">
        @error('name')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
      <div class="form-group">
        <label for="select-customer">{{ __('app.groups.column.customer') }}</label>
        <select class="form-control @error('customers') is-invalid @enderror select2 custom-select"
                id="select2"
                name="customers[]"
                multiple
                >
          @foreach ($customers as $customer)
            <option
            @if(isset($selected_customer) && in_array($customer->id, $selected_customer))
              selected
            @endif
            value="{{ $customer->id }}"
            >{{ $customer->name }}</option>
          @endforeach
        </select>
        @error('customers')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary">{{ __('app.global.submit') }}</button>
    </form>
  </div>
</div>

