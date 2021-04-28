<div>
  @php
    $table_id = str_replace('.', '-', $resources);
  @endphp
  @if ($withoutCard)
    <div class="table-responsive">
        <div class="d-flex justify-content-between">
          <div class="py-3">
            <h4 class="">{{ $title }}</h4>
          </div>
          <div class="row py-3 w-50">
            <div class="col-md-6 text-right">
              <x-components-button :title="__('hascrudactions::app.global.create')" :to="route($resources.'.create')">
                <x-slot name="icon">
                    <svg style="width:15px;margin-right:5px; margin-bottom:2px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                </x-slot>
              </x-components-button>
            </div>
            <div class="col-md-6">
              <select id="" class="form-control bulk-action">
                <option value="" disabled selected>@lang('hascrudactions::app.global.action')</option>
                <option value="bulk-destroy">@lang('hascrudactions::app.global.bulk-destroy')</option>
              </select>
            </div>
          </div>
        </div>
      <table class="table table-hover" id="{{ $table_id }}-table">
        <thead>
          <tr>
            @if (!$withoutCheckbox)
              @include('hascrudactions::partials.table.select-all')
            @endif
            {{ $thead }}
            @if (!$withoutTime)
              <th> {{ __('hascrudactions::app.global.created_at') }}</th>
            @endif
            @if (!$withoutAction)
              <th></th>
            @endif
          </tr>
        </thead>
        @if (isset($tbody))
          <tbody>
            <form method="POST" accept-charset="utf-8">
              {{ $tbody }}
            </form>
          </tbody>
        @endif
      </table>
    </div>
  @else
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="py-3">
            <h4 class="">{{ $title }}</h4>
          </div>
          <div class="row py-3 w-50">
            <div class="col-md-6 text-right">
              {{-- <x-components-link :route="$resources.'.create'" :title="__('hascrudactions::app.global.create')" class="btn btn-primary"></x-components-link> --}}
              <x-components-button :title="__('hascrudactions::app.global.create')" :to="route($resources.'.create')">
                <x-slot name="icon">
                  <svg style="width:15px;margin-right:5px; margin-bottom:2px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                  </svg>
                </x-slot>
              </x-components-button>
            </div>
            <div class="col-md-6">
              <select id="" class="form-control bulk-action">
                <option value="" disabled selected>@lang('hascrudactions::app.global.action')</option>
                <option value="bulk-destroy">@lang('hascrudactions::app.global.bulk-destroy')</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="{{ $table_id }}-table" width="100%">
            <thead>
              <tr>
                @if (!$withoutCheckbox)
                  @include('hascrudactions::partials.table.select-all')
                @endif
                {{ $thead }}
                @if (!$withoutTime)
                  <th> {{ __('hascrudactions::app.global.created_at') }}</th>
                @endif
                @if (!$withoutAction)
                  <th></th>
                @endif
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="card-footer">
      </div>
      <form method="POST" action="{{ route("{$resources}.bulkDestroy") }}" class="bulk-action-form">
        @csrf
        @method('DELETE')
      </form>
    </div>
  @endif
</div>

@push(config('hascrudactions.wrapper.javascript'))
  @if (!$withoutAction)
    <script>
      $(() => {
        let checked = $('input.select-all').prop('checked');
        $('#{{ $resources }}-table').on('change', 'input.select-all', function(event) {
          $('input:checkbox').not(this).prop('checked', this.checked);
        })

        $('#{{ $resources }}-table').on('change', 'input:checkbox', function(event) {
          if(event.target.classList[0] == 'select-row') {
            $('input.select-all').prop('checked', false);
          }
        })

        $('.bulk-action').on('change', function(ev) {
          let question = confirm('{{ trans('hascrudactions::app.global.suredelete') }}')
          if(question) {
            let checkbox = $('#{{ $resources }}-table input:checkbox');
            let input = '';
            let bulkActionForm = $('.bulk-action-form')
            for(let a = 0; a < checkbox.length; a++) {
              if($(checkbox[a]).attr('class') != 'select-all'){
                let checked = $(checkbox[a]).prop('checked');
                if(checked){
                  input += `<input type="hidden" name="ids[]" value="${$(checkbox[a]).attr('value')}">`
                }
              }
            }
            bulkActionForm.append(input);
            bulkActionForm.submit();
          } else {
            $('.bulk-action').val("")
          }
        })
      })
    </script>
  @endif
@endpush
