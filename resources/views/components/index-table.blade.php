<div>
  @isset ($topDiv)
  {{ $topDiv }}
  @endisset
  <div class="card">
    @if ($title)
      <div class="card-header">
        {{ $title }}
      </div>
    @endif
    <div class="card-body">
      <div class="table-responsive">
        @isset($content)
        {{ $content }}
        @endisset
      </div>
    </div>
    <div class="card-footer">
      {{ $title }}
    </div>
  </div>
  @isset ($bottomDiv)
  {{ $bottomDiv }}
  @endisset
</div>
