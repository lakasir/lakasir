<x-index-table title="" resources="detail-selling"
    :withoutaction="true" :withoutcheckbox="true" :withoutcard="true" :withoutTime="true">
    @slot('thead')
      <th> {{ __('app.sellings.column.detail.item_name') }} </th>
      <th> {{ __('app.sellings.column.detail.qty') }} </th>
      <th> {{ __('app.sellings.column.detail.price') }} </th>
      <th> {{ __('app.sellings.column.detail.profit') }} </th>
    @endslot
    @slot('tbody')
    @endslot
</x-index-table>
