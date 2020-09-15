<tr id="row-{{ $purchasingDetail->id }}">
  <td>
    @if (app()->environment(['local', 'staging']))
      <button type="button" class="btn btn-outline-info edit" id="{{ $purchasingDetail->id }}"><i class="fas fa-pen"></i></button>
      <button type="button" class="btn btn-outline-info add d-none" id="add-{{ $purchasingDetail->id }}"><i class="fas fa-save"></i></button>
    @endif
  </td>
  <td id="item-name-{{ $purchasingDetail->id }}" data-value="{{ $purchasingDetail->item->name }}">
    {{ optional($purchasingDetail->item)->name }}
  </td>
  <td id="item-qty-{{ $purchasingDetail->id }}" data-value="{{ $purchasingDetail->qty }}">
    {{ $purchasingDetail->qty }}
  </td>
  <td class="text-right" id="initial-price-{{ $purchasingDetail->id }}" data-value="{{ $purchasingDetail->initial_price }}">
    {{ price_format( $purchasingDetail->initial_price ) }}
  </td>
  <td class="text-right" id="selling-price-{{ $purchasingDetail->id }}" data-value="{{ $purchasingDetail->selling_price }}">
    {{ price_format( $purchasingDetail->selling_price ) }}
  </td>
  <td class="text-right" id="row-total-{{ $purchasingDetail->id }}" data-value="{{ $purchasingDetail->row_total }}">
    {{ $purchasingDetail->row_total }}
  </td>
</tr>
