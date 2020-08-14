<div class="row">
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col"> {{ __('app.global.action') }}</th>
          <th scope="col" width="30%"> {{ __('app.purchasings.column.items.name') }}</th>
          <th scope="col" width="10%"> {{ __('app.purchasings.column.items.qty') }}</th>
          <th scope="col" width="20%" class="text-right"> {{ __('app.purchasings.column.items.initial_price') }}</th>
          <th scope="col" width="20%" class="text-right"> {{ __('app.purchasings.column.items.selling_price') }}</th>
          <th scope="col" width="20%" class="text-right"> {{ __('app.purchasings.column.items.total') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data->purchasingDetails as $purchasingDetail)
          <tr>
            <td>
              @if (app()->environment(['local', 'staging']))
                <button type="button" class="btn btn-outline-info"><i class="fas fa-pen"></i></button>
              @endif
            </td>
            <td>
              {{ $purchasingDetail->item->name }}
            </td>
            <td>
              {{ $purchasingDetail->qty }}
            </td>
            <td class="text-right">
              {{ price_format( $purchasingDetail->initial_price ) }}
            </td>
            <td class="text-right">
              {{ price_format( $purchasingDetail->selling_price ) }}
            </td>
            <td class="text-right">
              {{ $purchasingDetail->row_total }}
            </td>
          </tr>
        @endforeach
        {{-- <tr> --}}
          {{--   <td colspan="6"> --}}
            {{--     <button type="button" class="btn btn-outline-success"><i class="fas fa-plus"></i></button> --}}
            {{--   </td> --}}
          {{-- </tr> --}}
        <tr>
          <td colspan="4"></td>
          <th class="text-right">{{ __('app.global.total') }}</th>
          <td class="text-right">
            <b>{{ $data->total_purchasing }}</b>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
