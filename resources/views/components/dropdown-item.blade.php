<div>
  @if (isset($option->confirm) && $option->confirm)
    <a class="dropdown-item {{ $option->className }} has-icon"
       @isset($option->data)
         @foreach ($option->data as $key => $data)
           data-{{$key}}="{{$data}}"
         @endforeach
       @endisset
      href="#"
      onclick="event.preventDefault();
      confirm('{{ $option->confirm }}') ?
      document.getElementById('delete-form-{{ $option->extend }}').submit() : '';">
      @if (isset($option->icon))
        {!! $option->icon !!}
      @endif
      {!! $option->title ?? $option->extend !!}
      <form id="delete-form-{{ $option->extend }}" action="{{ $option->url }}" method="POST" style="display:none">
        @csrf
        @if (isset($option->method) && $option->method)
          @method($option->method)
        @endif
      </form>
    </a>
  @else
    <a class="dropdown-item {{ $option->className }} has-icon"
       @isset($option->data)
         @foreach ($option->data as $key => $data)
           data-{{$key}}="{{$data}}"
         @endforeach
       @endisset
      href="{{ $option->url ?? "#" }}" >
      @if (isset($option->icon))
        {!! $option->icon !!}
      @endif
      {!! $option->title ?? $option->extend !!}
    </a>
  @endif
</div>
