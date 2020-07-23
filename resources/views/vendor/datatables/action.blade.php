<div class="dropdown dropleft">
  <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @if (isset($editRoute))
      <a href="{{ $editRoute }}" class="dropdown-item"> {{ __('app.global.edit') }}</a>
    @endif
    @if (isset($deleteRoute))
      <button class="delete dropdown-item"> {{ __('app.global.delete') }}</button>
    @endif
  </div>
</div>
