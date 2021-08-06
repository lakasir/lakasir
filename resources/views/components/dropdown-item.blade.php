<div>
  @if (isset($option->confirm) && $option->confirm)
    <a class="dropdown-item {{ $option->className }} has-icon"
      href="#"
      onclick="event.preventDefault();
      confirm('{{ $option->confirm }}') ?
      document.getElementById('delete-form-{{ $option->extend }}').submit() : '';">
      @if (isset($option->icon))
        {!! $option->icon !!}
      @endif
      <span>{!! $option->title ?? $option->extend !!}</span>
      <form id="delete-form-{{ $option->extend }}" action="{{ $option->url }}" method="POST" style="display:none">
        @csrf
        @if (isset($option->method) && $option->method)
          @method($option->method)
        @endif
      </form>
    </a>
  @else
    <a class="dropdown-item {{ $option->className }} has-icon"
      href="{{ $option->url ?? "#" }}" >
      @if (isset($option->icon))
        {!! $option->icon !!}
      @endif
      <span>{!! $option->title ?? $option->extend !!}</span>
    </a>
  @endif
</div>
