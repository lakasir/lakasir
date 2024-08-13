<x-filament-panels::page>
  <div class="flex gap-x-4">
    <x-filament::section class="w-full">
      <div class="h-screen overflow-auto" id="printElement">
        <div
          style="column-gap: {{ $data['horizontal_gap'] }}px; row-gap: {{ $data['vertical_gap'] }}px;"
          class="grid grid-cols-{{ $data['items_per_row'] ?? 1 }} gap-x-4">
          @foreach($products as $product)
            <div class="border border-black px-2 text-center py-2">
              <p class="text-center font-bold">{{ $product['name'] }}</p>
              <p>{{ $product['price'] }}</p>
              <div class="flex justify-center">
                {!!$product['barcode_html']!!}
              </div>
              <p>{{ $product['barcode'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </x-filament::section>
    <div class="w-1/2">
      {{ $this->form }}
    </div>
  </div>
</x-filament-panels::page>
@script()
<script>
  document.getElementById('printLabelButton').addEventListener('click', async () => {
    await $wire.applySetting();

    const printContents = document.getElementById("printElement").innerHTML;
    const originalContents = document.body.innerHTML;


    document.body.innerHTML = printContents;

    window.print();

    window.location.reload();
  });
</script>
@endscript
