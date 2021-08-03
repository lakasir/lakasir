<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <svg style="width:16px; margin-right:2px; margin-bottom:2px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @if (!isset($withoutedit))
      <a class="dropdown-item has-icon" href="{{route($resources.'.edit', $model->id)}}">
        <span><svg style="width:16px; margin-right:5px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
          </svg></span> {{ trans('hascrudactions::app.global.edit') }}
      </a>
    @endif
    @if (!isset($withoutdetail))
      <a class="dropdown-item has-icon" href="{{route($resources.'.show', $model->id)}}">
        <span><svg style="width:16px; margin-right:5px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg></span> {{ trans('hascrudactions::app.global.detail') }}
      </a>
    @endif
    @if (!isset($withoutdelete))
      <a class="dropdown-item delete-row has-icon"
         id="{{ $model->id }}"
         href="{{ $delete }}"
         onclick="event.preventDefault();
         confirm('{{ trans('hascrudactions::app.global.suredelete') }}') ?
         document.getElementById('delete-form-{{ $model->id }}').submit() : '';">

        <span><svg style="width:16px; margin-right:5px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg></span>  {{ __('hascrudactions::app.global.delete') }}
      </a>
      <form id="delete-form-{{ $model->id }}" action="{{ $delete }}" method="POST" style="display:none">
        @csrf
        @method('DELETE')
      </form>
    @endif
  </div>
</div>

