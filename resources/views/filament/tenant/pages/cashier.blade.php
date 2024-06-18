@php
use Filament\Facades\Filament;
use App\Features\{PaymentShortcutButton};

@endphp
<div class="">
  <div class="grid grid-cols-3 gap-x-4">
    <div class="col-span-2">
      {{ $this->table }}
    </div>
    <div class="fixed right-0 w-1/3 h-screen pb-10 overflow-y-scroll">
      <div class="px-4 mt-4 space-y-2 h-screen">
        <div class="flex justify-between" x-data="fullscreen">
          <p class="text-2xl font-bold">{{ __('Orders details') }}</p>
          <div class="flex gap-x-2">
            <x-heroicon-m-arrow-path
              x-on:mousedown="document.location.reload()"
              class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer"
            />
            <x-heroicon-o-arrows-pointing-out
              x-on:mousedown="requestFullscreen"
              class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer"
            />
          </div>
        </div>
        <div class="flex justify-between">
          <p class="">{{ Filament::auth()->user()->cashier_name }}</p>
        </div>
        <div class="flex justify-between items-center">
          <p class="text-2xl font-bold mb-2">{{ __('Current Orders') }}</p>
          <div class="flex gap-x-1">
            <a
              href="/member/sellings"
              class="py-1 px-4 flex justify-center items-center bg-gray-100 rounded-lg gap-x-1 text-gray-500">
                <x-heroicon-o-arrow-left class="h-4 w-4 text-gray-500"/>
                <p>{{ __('Back') }} </p>
            </a>
            <button class="py-1 px-4 bg-red-200 text-red-500 rounded-lg flex gap-x-1 items-center"
              wire:confirm="Are you sure you want to clear all of the items?"
              wire:click.prevent="clearCart" >
                <x-heroicon-o-trash class="h-4 w-4 text-red-500"/>
                <p>{{ __('Clear') }} </p>
            </button>
          </div>
        </div>
        <div class="overflow-y-scroll min-h-40 max-h-[35%] overflow-auto"
          @forelse($cartItems as $item)
            <div class="flex justify-between mb-2 border rounded-lg bg-white dark:border-gray-900 dark:bg-gray-900 px-4 py-2" key="{{ rand() }}">
              <div class="flex items-center space-x-3">
                <img
                class="object-cover h-16 w-20 rounded-lg"
                src=" {{ $item->hero_image }}"/>
                <div class="space-y-3">
                  <p class="font-semibold"> {{ $item->product->name }}</p>
                  <div class="flex space-x-3 h-8">
                    <button
                      class="!bg-[#ff6600] rounded-lg px-2 py-1"
                      wire:click.stop="addCart( {{ $item->product_id  }} )"
                      wire:loading.attr="disabled"
                      >
                      <x-heroicon-o-plus-small class="!text-white h-4 w-4"/>
                    </button>
                    <p class="my-auto">{{ $item->qty }}</p>
                    <button
                      class="!bg-gray-100 rounded-lg px-2 py-1"
                      wire:click="reduceCart({{  $item->product_id  }})"
                      wire:loading.attr="disabled"
                      >
                      <x-heroicon-o-minus-small class="!text-green-900 h-4 w-4"/>
                    </button>
                    <button
                      class="!bg-danger-100 rounded-lg px-2 py-1"
                      wire:click="deleteCart({{ $item->id  }})"
                      wire:loading.attr="disabled"
                      >
                      <x-heroicon-o-trash class="!text-danger-900 h-4 w-4"/>
                    </button>
                  </div>
                </div>
              </div>
              <div class="items-center text-right space-y-2">
                <p class="font-semibold text-[#ff6600]">{{ $item->price_format_money }}</p>
                <div class="flex justify-end">
                <x-filament::input.wrapper class="w-1/2">
                  <x-filament::input
                    type="text"
                    id="{{ $item->product->name }}-{{ $item->id }}"
                    value="{{ $item->discount_price == 0  ? '' : $item->discount_price }}"
                    wire:keyup.debounce.500ms="addDiscountPricePerItem({{  $item  }}, parseFloat($event.target.value.replace(/,/g, '')))"
                    placeholder="{{ __('Discount') }}"
                    class="text-right w-1/2"
                    inputMode="numeric"
                    x-mask:dynamic="$money($input)"
                  />
                </x-filament::input.wrapper>
                </div>
                @if($item->discount_price && $item->discount_price > 0)
                  <p class="font-semibold text-[#ff6600]">{{ $item->final_price_format }}</p>
                @endif
              </div>
            </div>
          @empty
            <div class="flex justify-center items-center h-40 border bg-white rounded-lg dark:border-gray-900 dark:bg-gray-900">
              <x-heroicon-o-x-mark class="text-gray-900 dark:text-white h-10 w-10"/>
                <p class="text-3xl text-gray-600 dark:text-white">{{ __('No item') }}</p>
            </div>
          @endforelse
        </div>
        <div>
          <div class="bg-white px-4 py-2 w-full border rounded-lg dark:border-gray-900 dark:bg-gray-900 dark:text-white text-gray-600">
            @include('filament.tenant.pages.cashier.detail')
          </div>
        </div>
        <div>
          <div class="bg-white px-4 py-2 w-full border rounded-lg dark:border-gray-900 dark:bg-gray-900 dark:text-white text-gray-600">
            @include('filament.tenant.pages.cashier.total')
          </div>
        </div>
        <button
          class="py-4 px-2 bg-[#ff6600] text-white rounded-lg w-full"
          x-on:mousedown="$dispatch('open-modal', {id: 'proceed-the-payment'})"
          >{{ __('Proceed to payment') }}</button>
      </div>
    </div>
  </div>
  <x-filament::modal
    id="edit-detail"
    width="2xl"
    >
    <form wire:submit.prevent="storeCart">
      <x-slot name="heading">
        <p id="titleEditDetail">{{ __('Edit detail') }}</p>
      </x-slot>
    {{ $this->storeCartForm }}
    <x-filament::button type="submit" class="mt-10">
      {{ __('Save') }}
    </x-filament::button>
    </form>
  </x-filament::modal>
  <x-filament::modal
    id="proceed-the-payment"
    width="5xl">
    <form wire:submit.prevent="proceedThePayment">
    <div class="my-2 grid grid-cols-2 gap-x-4">
      <div x-data="detail">
        <div class="rounded-lg">
          <div class="mb-4 grid grid-cols-4 gap-1">
            <template x-for="paymentMethod in paymentMethods">
              <div
                x-on:click="cartDetail['payment_method_id'] = paymentMethod.id; $wire.cartDetail['payment_method_id'] = paymentMethod.id;"
                class="cursor-pointer hover:scale-105 border border-[#ff6600] rounded-md px-4 py-2 flex justify-center dark:text-white text-sm"
                :class="cartDetail['payment_method_id']  == paymentMethod.id ? 'bg-[#ff6600] text-white' : 'dark:bg-gray-900 '"
                x-text="paymentMethod.name.substring(0, 8)">
              </div>
            </template>
          </div>
          <x-filament::input.wrapper
            x-show="paymentMethods.filter((pm) => pm.is_credit)[0]?.id == cartDetail['payment_method_id']"
            :valid="! $errors->has('due_date')"
            class="mb-2">
            <x-slot name="prefix">
              {{ __('Due date') }}
            </x-slot>
            <x-filament::input
              type="date"
              wire:model="cartDetail.due_date"
            />
          </x-filament::input.wrapper>
          <div class="mb-4">
            @include('filament.tenant.pages.cashier.total')
          </div>
          @error('payed_money') <span class="error text-danger-500">{{ $message }}</span> @enderror
          <input
            id="display"
            class="w-full p-2 border border-gray-300 rounded-md text-lg text-right dark:bg-gray-900 dark:text-white h-20 text-black @error('payed_money') 'border-danger-500' @enderror"
            focus
            :disabled="isTouchScreen"
            x-mask:dynamic="$money($input)"
            x-on:keyup="changes"
            x-ref="payedMoney"
            inputMode="numeric"
          >
          <div class="grid grid-cols-3 gap-4 mt-4" id="calculator-button-shortcut">
          </div>
          <div class="grid grid-cols-3 gap-4 mt-4" id="calculator-button">
            <button type="button" class="col-span-3 bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append('no_changes')">{{ __('No change') }}</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(7)">7</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(8)">8</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(9)">9</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(4)">4</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(5)">5</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(6)">6</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(1)">1</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(2)">2</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(3)">3</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append('.')">.</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg" x-on:click="append(0)">0</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg flex justify-center items-center" x-on:click="append('backspace')">
              <x-filament::icon
                icon="heroicon-o-backspace"
                class="h-5 w-5 text-gray-500 dark:text-white"
              />
            </button>
            <button
              wire:loading.attr="disabled"
              type="submit" class="col-span-3 bg-[#ff6600] hover:bg-[#ff6611] p-2 rounded-md text-white text-lg flex justify-center items-center gap-x-2">
              <div wire:loading>
                <x-filament::loading-indicator class="h-5 w-5"/>
              </div>
              {{ __('Pay it') }}
            </button>
          </div>
        </div>
      </div>
      <div class="overflow-y-scroll max-h-[80vh]">
        @if ($errors->any())
          @foreach ($errors->all() as $error)
            <p class="error text-danger-500 text-lg text-center w-full">{{ $error }}</p>
          @endforeach
        @endif
        @include('filament.tenant.pages.cashier.items')
      </div>
    </div>
    </form>
  </x-filament::modal>
  <x-filament::modal
    id="success-modal"
    width="xl"
    :close-by-clicking-away="false"
    :close-by-escaping="false"
    >
    <div class="flex justify-center items-center flex-col">
      <x-heroicon-o-check-circle style="color: rgb(34 197 94); width: 200px" />
      <p class="font-bold text-4xl">@lang('Success')</p>
      <p>@lang('Your transaction was successfull')</p>
    </div>
    <x-slot name="footer">
      <div class="grid grid-cols-2 gap-x-2">
        <x-filament::button icon="heroicon-m-printer" id="printReceiptButton">
          {{ __('Print') }}
        </x-filament::button>
        <x-filament::button color="gray" x-on:click="$dispatch('close-modal', {id: 'success-modal'})">
          {{ __('Close') }}
        </x-filament::button>
      </div>
    </x-slot>
  </x-filament::modal>
</div>

@script
<script>
  let selling = null;
  $wire.on('selling-created', (event) => {
    $wire.dispatch('close-modal', {id: 'proceed-the-payment'});

    $wire.dispatch('open-modal', {id: 'success-modal'});
    selling = event.selling;
  });
  document.getElementById("printReceiptButton").addEventListener('click', (event) => {
    let about = @js($about);

    try {
      if (localStorage.printer == undefined) {
        new FilamentNotification()
          .title('@lang('You should choose the printer first in printer setting')')
          .danger()
          .actions([
            new FilamentNotificationAction('Setting')
              .icon('heroicon-o-cog-6-tooth')
              .button()
              .url('/member/printer'),
          ])
          .send()
      } else {
        printToUSBPrinter(selling, about);
      }
    } catch (error) {
      console.error(error);
    }
  });

  Alpine.data('fullscreen', () => {
    return {
      isFullscreen: false,
      requestFullscreen() {
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen();
          isFullscreen = true;
        } else {
          document.exitFullscreen();
          isFullscreen = false;
        }
      }
    }
  });
  Alpine.data('detail', () => {
    return {
      isTouchScreen() {
        return ( 'ontouchstart' in window ) ||
          ( navigator.maxTouchPoints > 0 ) ||
          ( navigator.msMaxTouchPoints > 0 );
      },
      displayValue: '',
      paymentMethods: $wire.entangle('paymentMethods'),
      cartDetail: @js($cartDetail),
      subtotal: $wire.entangle('total_price'),
      append(number) {
        if(number == 'no_changes') {
          this.$refs.payedMoney.value = this.moneyFormat(this.subtotal);
          this.changes();
          return;
        }
        if(number == 'backspace') {
          this.displayValue = this.displayValue.slice(0, -1);
          this.$refs.payedMoney.value = this.moneyFormat(this.displayValue);
          this.changes();
          return;
        }
        this.displayValue += number;
        this.$refs.payedMoney.value = this.moneyFormat(this.displayValue);
        this.changes();
      },
      moneyFormat(number) {
        const formatter = new Intl.NumberFormat({
          style: 'currency',
          currency: '{{ $currency }}',
        });

        return formatter.format(number);
      },
      changes() {
        let num = parseFloat(this.$refs.payedMoney.value.replace(/,/g, ''));
        num = isNaN(num) ? 0 : num;
        $wire.cartDetail['money_changes'] = num - (this.subtotal);
        $wire.cartDetail['payed_money'] = num;
        this.$refs.moneyChanges.textContent = this.moneyFormat($wire.cartDetail['money_changes']);
      }
    }
  });

  let barcodeData = '';
  let barcodeTimeout;
  let scannerEnabled = true;
  let modalOpened = false;
  let input;
  let index;

  $wire.on('open-modal', (event) => {
    if (event.inputId != undefined) {
      let inputId = event.inputId;
      let title = event.title;
      let titleModal = document.getElementById("titleEditDetail");
      titleModal.innerHTML = title;
      index = event.index;
      input = document.getElementById(inputId);
      const result = [...(input.parentNode.parentNode.parentNode.parentNode.parentNode.children)].forEach((child, i) => {
        if (i != index) {
          child.classList.add('hidden');
        }
      });
      input.classList.remove('hidden');
    }
    let calculatorBtn = document.getElementById('calculator-button-shortcut');
    calculatorBtn.innerHTML = '';
    let totalPrice = $refs.total.getAttribute('data-value');
    const suggestionValues = [10000, 15000, 20000, 30000, 50000, 100000];
    if("@js(feature(PaymentShortcutButton::class))" == 'true') {
      if (totalPrice < 100000) {
        suggestionValues.forEach(value => {
          if (totalPrice < value) {
            const button = document.createElement('button');
            button.setAttribute('type', 'button')
            button.setAttribute('x-on:click', `append(${value})`);
            button.className = 'bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg';
            button.textContent = value;
            calculatorBtn.appendChild(button);
          }
        });
      }
    }
    modalOpened = true;
  });

  $wire.on('close-modal', (event) => {
    if(input != undefined) {
      let titleModal = document.getElementById("titleEditDetail");
      titleModal.innerHTML = '@lang('Edit detail')';
      const result = [...(input.parentNode.parentNode.parentNode.parentNode.parentNode.children)].forEach((child, i) => {
        if (i != index) {
          child.classList.remove('hidden');
        }
      });
      input.classList.add('hidden');
      input = undefined
    }
    modalOpened = false;
  });

  document.addEventListener('keypress', (event) => {
    if (modalOpened) {
      return;
    }

    if (!scannerEnabled) {
      return;
    }
    if (barcodeTimeout) {
      clearTimeout(barcodeTimeout);
    }

    if (event.key === 'Enter') {
      console.log('Barcode scanned:', barcodeData);
      $wire.addCartUsingScanner(barcodeData);

      barcodeData = '';
      scannerEnabled = false;

      setTimeout(() => {
        scannerEnabled = true;
      }, 2000);
    } else {
      barcodeData += event.key;
    }

    barcodeTimeout = setTimeout(() => {
      barcodeData = '';
    }, 500);
  });
</script>
@endscript


