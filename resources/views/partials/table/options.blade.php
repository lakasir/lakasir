<div class="dropdown">
  <button
     class="btn btn-outline-secondary btn-rounded btn-sm dropdown-toggle"
     id="dropdownMenuOffset"
     data-toggle="dropdown"
     aria-haspopup="true"
     aria-expanded="false"
     >
    <i class="fas fa-ellipsis-h"></i>
    @lang('app.global.options')
  </button>
  <div class="dropdown-menu" aria-labelledby="dLabel">
    @foreach ($options as $option)
      @if ($option->show)
        <x-dropdown-item :option="$option"></x-dropdown-item>
      @endif
    @endforeach
  </div>
</div>
