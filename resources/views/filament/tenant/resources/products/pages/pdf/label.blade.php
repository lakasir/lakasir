<div style="margin-left: 2px;">
  <div style="margin-bottom: 10rem;">
    <h3>Barcode</h3>
  </div>
  @php
    $row = 0;
    $first = 0;
  @endphp
  @for ($index = 0; $index < $count; $index++)
    <div style="display: inline-block; margin-right: 5px; margin-bottom: 10px; max-width: 130; border: 1px solid #000; padding: 4px">
      <p align="center" style="font-weight: bold; text-wrap:wrap;">{{ $product->name }}</p>
      {!! $barcode !!}
      <p align="center" style="font-weight: bold; text-wrap:wrap;">{{ Number::format($product->selling_price) }}</p>
    </div>

    @if(($index + 1) % 4 == 0)
      @php($row++)
      @php($first = $index)
    @endif

    @if(($row + 1) % 6 == 0 && $first == $index)
      <div style="margin-top: 40rem;"></div>
      {{-- <br style="page-break-before: always;"> --}}
    @endif

    {{-- @if(($index + 1) % 50 == 0 ) --}}
    {{--   <br style="page-break-before: always;"> --}}
    {{-- @endif --}}
  @endfor
</div>
