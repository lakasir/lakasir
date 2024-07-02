<div style="margin-left: 2px;">
  @for ($index = 0; $index < $count; $index++)
    <div style="display: inline-block; margin-right: 16px; margin-bottom: 10px; max-width: 130;">
      <p align="center" style="font-weight: bold; text-wrap:wrap;">{{ $product->name }}</p>
      {!! $barcode !!}
      <p align="center" style="font-weight: bold; text-wrap:wrap;">{{ Number::format($product->selling_price) }}</p>
    </div>

    @if(($index + 1) % 44 == 0 )
      <br style="page-break-before: always;">
    @endif
  @endfor
</div>
