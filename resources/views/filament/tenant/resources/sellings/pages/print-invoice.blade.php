@php
use App\Models\Tenants\Setting;

@endphp
<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif

    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers
            :active-manager="$this->activeRelationManager"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        />
    @endif
    <x-filament::section>
      <div class="table w-full my-4">
        <table class="table ns-table w-full">
          <thead class="text-secondary">
            <tr>
              <th width="400" class="p-2 border">@lang('Product')</th>
              <th width="200" class="p-2 border">@lang('Unit price')</th>
              <th width="200" class="p-2 border">@lang('Quantity')</th>
              <th width="200" class="p-2 border">@lang('Discount')</th>
              <th width="200" class="p-2 border">@lang('Total price')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($record->sellingDetails as $detail)
              <tr>
                <td class="p-2 border">
                  <h3 class="text-primary">{{ $detail->product->name }}</h3><span class="text-sm text-secondary"></span></td>
                <td class="p-2 border text-center text-primary">{{ Number::currency($detail->price, Setting::get('currency', 'IDR')) }}</td>
                <td class="p-2 border text-center text-primary">{{ $detail->qty }}</td>
                <td class="p-2 border text-center text-primary">{{ Number::currency($detail->discount_price, Setting::get('currency', 'IDR')) }}</td>
                <td class="p-2 border text-center text-primary">{{ Number::currency($detail->total_price, Setting::get('currency', 'IDR')) }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="font-semibold">
            <tr>
              <td class="p-2 border text-center text-primary" colspan="3"></td>
              <td class="p-2 border text-primary text-left">Sub Total</td>
              <td class="p-2 border text-right text-primary">{{ Number::currency($record->total_price, Setting::get('currency', 'IDR')) }}</td>
            </tr>
            <tr>
              <td class="p-2 border text-center text-primary" colspan="3"></td>
              <td class="p-2 border text-primary text-left">Discount</td>
              <td class="p-2 border text-right text-primary">{{ Number::currency($record->total_discount_per_item + $record->discount_price, Setting::get('currency', 'IDR')) }}</td>
            </tr>
            <!---->
            <tr>
              <td class="p-2 border text-center text-primary" colspan="3"></td>
              <td class="p-2 border text-primary text-left">Total</td>
              <td class="p-2 border text-right text-primary">{{ Number::currency($record->grand_total_price, Setting::get('currency', 'IDR')) }}</td>
            </tr>
            <tr>
              <td class="p-2 border text-center text-primary" colspan="3"></td>
              <td class="p-2 border text-primary text-left">@lang('Payed money')</td>
              <td class="p-2 border text-right text-primary">{{ Number::currency($record->payed_money, Setting::get('currency', 'IDR')) }}</td>
            </tr>
            <tr>
              <td class="p-2 border text-center text-primary" colspan="3"></td>
              <td class="p-2 border text-primary text-left">@lang('Money changes')</td>
              <td class="p-2 border text-right text-primary">{{ Number::currency($record->money_changes, Setting::get('currency', 'IDR')) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </x-filament::section>
</x-filament-panels::page>
@script()
<script>
</script>
@endscript
