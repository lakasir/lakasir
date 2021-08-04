<div>
  @isset ($topDiv)
  {{ $topDiv }}
  @endisset
  <div class="card">
    <div class="card-header">
      {{ $title }}
    </div>
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
