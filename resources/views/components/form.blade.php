<div>
  <div class="card col-md-{{ $size }} p-0">
    <form action="{{ $route }}" method="POST">
      <div class="card-header">
        <h4>{{ $title }}</h4>
      </div>
      <div class="card-body">
        @csrf
        @method($method)
        {{ $slot }}
      </div>
      <div class="card-footer">
        <div class="col-12">
          <v-button float="left" type="submit" text="{{ __('app.global.submit') }}" icon="fas fa-check" ></v-button>
        </div>
      </div>
    </form>
  </div>
</div>
