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
    @foreach ($actions as $action)
      @if ($action->show)
        <x-dropdown-item :action="$action"></x-dropdown-item>
      @endif
    @endforeach
  </div>
</div>
