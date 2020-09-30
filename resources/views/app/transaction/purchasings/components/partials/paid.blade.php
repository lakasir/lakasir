@if ($paid)
  <div class="row">
    <div class="col">
      <span class="badge badge-success"> {{ __('app.purchasings.paid.true') }}</span>
    </div>
    @can('update-paid-purchasing')
      <div class="col">
        <a class="btn btn-sm btn-{{ $paid ? 'success' : 'info' }}"
           id="{{ $model->id }}"
           data-confirm=" {{ __('app.purchasings.question_paid', ['paid'=> $paid ? 'Undo Pay' : 'Pay']) }}"
           data-method="POST" href="{{ route('update-paid-purchasing', $model->id) }}">
           <i class="fas fa-arrow-down"></i>
        </a>
      </div>
    @endcan
    <!-- Default switch -->
  </div>
@else
  <div class="row">
    <div class="col">
      <span class="badge badge-info"> {{ __('app.purchasings.paid.false') }}</span>
    </div>
    @can('update-paid-purchasing')
      <div class="col">
        <a class="btn btn-sm btn-{{ $paid ? 'success' : 'info' }}"
           id="{{ $model->id }}"
           data-confirm=" {{ __('app.purchasings.question_paid', ['paid'=> $paid ? 'Undo Pay' : 'Pay']) }}"
           data-method="POST" href="{{ route('update-paid-purchasing', $model->id) }}">
           <i class="fas fa-arrow-up"></i>
        </a>
      </div>
    @endcan
  </div>
@endif
