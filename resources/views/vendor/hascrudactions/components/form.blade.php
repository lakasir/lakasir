<div class="d-flex justify-content-center">
  <div class="{{ $card ? 'card' : ''}} col-md-{{ $size }} p-0">
    <form action="{{ $route }}" method="POST" enctype="multipart/form-data" onsubmit="return to(this)">
      @if ($card)
        <div class="card-header">
          <h4>{{ $title }}</h4>
        </div>
      @endif
      <div class="{{ $card ? 'card-body' : '' }}">
        @csrf
        @method($method)
        {{ $slot }}
      </div>
      <div class="{{ $card ? 'card-footer' : '' }}">
        <div class="{{ $card ? '' : 'col-12' }}">
          <x-components-button :title="__('hascrudactions::app.global.submit')">
            <x-slot name="icon">
              <svg style="width:15px; margin-right:2px; margin-bottom:4px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
              </svg>
            </x-slot>
          </x-components-button>
        </div>
      </div>
    </form>
  </div>
</div>

