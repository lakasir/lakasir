<div>
  @if (isset($action->confirm))
    <a class="dropdown-item {{ $action->className }} has-icon"
      href="#"
      onclick="event.preventDefault();
      confirm('{{ $action->confirm }}') ?
      document.getElementById('delete-form-{{ $action->extend }}').submit() : '';">
      @if (isset($action->icon))
        {!! $action->icon !!}
      @endif
      <span>{!! $action->title ?? $action->extend !!}</span>
      <form id="delete-form-{{ $action->extend }}" action="{{ $action->url }}" method="POST" style="display:none">
        @csrf
        @if (isset($action->method))
          @method($action->method)
        @endif
      </form>
    </a>
  @else
    <a class="dropdown-item {{ $action->className }} has-icon"
      href="{{ $action->url ?? "#" }}" >
      @if (isset($action->icon))
        {!! $action->icon !!}
      @endif
      <span>{!! $action->title ?? $action->extend !!}</span>
    </a>
  @endif
</div>
