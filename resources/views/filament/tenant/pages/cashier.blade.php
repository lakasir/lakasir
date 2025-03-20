@php
use Filament\Facades\Filament;
use App\Features\{PaymentShortcutButton, SellingTax, Discount};

@endphp
<div class="">
  <div class="grid grid-cols-3 gap-x-4">
    <div class="col-span-2">
      {{ $this->table }}
    </div>
    <div class="fixed right-0 w-1/3 h-screen pb-10 overflow-y-scroll">
      <div class="px-4 mt-4 space-y-2 h-screen">
        <div class="flex justify-between items-center" x-data="fullscreen">
          <p class="text-xl font-semibold">{{ __('Orders details') }}</p>
          <div class="flex items-center">
            <div class="xl:flex gap-x-2 hidden items-center">
              <a
                href="/member/sellings"
                class="py-1 px-4 flex justify-center items-center bg-gray-100 rounded-lg gap-x-1 text-gray-500">
                <x-heroicon-o-arrow-left class="h-4 w-4 text-gray-500"/>
                  <p class="hidden lg:block">{{ __('Back') }} </p>
              </a>
            </div>
            <div class="gap-x-2">
              <x-filament::dropdown placement="top-start">
                <x-slot name="trigger">
                  <x-heroicon-o-ellipsis-vertical class="h-8 w-8 text-gray-900 dark:text-gray-300 cursor-pointer" />
                </x-slot>

                <x-filament::dropdown.list>
                  <x-filament::dropdown.list.item x-on:mousedown="document.location.reload()" >
                    <div class="flex gap-x-2">
                      <x-heroicon-m-arrow-path class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer" />
                        <p>{{ __('Reload') }} </p>
                    </div>
                  </x-filament::dropdown.list.item>

                  <x-filament::dropdown.list.item x-on:mousedown="requestFullscreen">
                    <div class="flex gap-x-2">
                      <x-heroicon-o-arrows-pointing-out class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer" />
                        <p>{{ __('Fullscreen') }} </p>
                    </div>
                  </x-filament::dropdown.list.item>
                  <x-filament::dropdown.list.item>
                    <p class="flex gap-x-2"
                      wire:confirm="Are you sure you want to clear all of the items?"
                      wire:click.prevent="clearCart">
                    <x-heroicon-o-trash class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer"/> <span>{{ __('Clear') }} </span> </p>
                  </x-filament::dropdown.list.item>

                </x-filament::dropdown.list>
              </x-filament::dropdown>
            </div>
          </div>
        </div>
        <hr/>
        <div class="lg:flex hidden justify-between">
          <p class="">{{ Filament::auth()->user()->cashier_name }}</p>
        </div>
        <div class="flex justify-between items-center">
          <p class="hidden lg:block text-2xl font-semibold mb-2">{{ __('Current Orders') }}</p>
          <div class="flex gap-x-1"></div>
        </div>
        <div class="overflow-y-scroll min-h-40 max-h-[35%] overflow-auto" wire:loading.class="opacity-20" wire:target="addCart,reduceCart,deleteCart,addDiscountPricePerItem,addCartUsingScanner">
          @forelse($cartItems as $item)
            <div class="mb-2 border rounded-lg bg-white dark:border-gray-900 dark:bg-gray-900 px-4 py-2" id="{{ $item->id }}" key="{{ rand() }}">
              <div class="grid items-center space-x-3">
                <div class="flex justify-between">
                  <p class="font-semibold"> {{ $item->product->name }}</p>
                  <p class="font-semibold text-lakasir-primary">{{ $item->price_format_money }}</p>
                </div>
              </div>
              <div class="grid grid-cols-2 items-center text-right space-y-2 py-2">
                <div class="col-span-2">
                  @feature(Discount::class)
                  <div class="flex justify-end mb-1">
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
                  @endfeature
                  @if($item->discount_price && $item->discount_price > 0)
                    <p class="font-semibold text-lakasir-primary">{{ $item->final_price_format }}</p>
                  @endif
                </div>
              </div>
                <div class="flex space-x-3 h-8">
                  <button
                    class="!bg-lakasir-primary rounded-lg px-2 py-1"
                    wire:click.stop="addCart( {{ $item->product_id  }} )"
                    wire:loading.attr="disabled"
                    >
                    <x-heroicon-o-plus-small class="!text-white h-4 w-4"/>
                  </button>
                    <x-filament::input.wrapper class="w-20" x-data="cart">
                      <x-filament::input
                        type="text"
                        id="{{ $item->product->name }}-{{ $item->id }}-qty-{{ rand() }}"
                        data-value="{{ $item->qty }}"
                        value="{{ $item->qty }}"
                        x-on:keyup.debounce.500ms="(e) => add('{{ $item->product_id }}', e.target.value)"
                        placeholder="{{ __('Discount') }}"
                        class="text-right w-1/2"
                        inputMode="numeric"
                        />
                      </x-filament::input.wrapper>
                  <button
                    class="!bg-gray-100 rounded-lg px-2 py-1"
                    x-on:click="$wire.reduceCart({{  $item->product_id  }});"
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
                  <livewire:price-setting :cart-item="$item" key="{{ $item->id }}" />
                </div>
            </div>
          @empty
            <div class="flex justify-center items-center h-40 border bg-white rounded-lg dark:border-gray-900 dark:bg-gray-900">
              <x-heroicon-o-x-mark class="text-gray-900 dark:text-white h-10 w-10 hidden lg:block"/>
                <p class="text-xl lg:text-3xl text-gray-600 dark:text-white">{{ __('No item') }}</p>
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
          class="py-4 px-2 bg-lakasir-primary text-white rounded-lg w-full"
          x-on:mousedown="$dispatch('open-modal', {id: 'proceed-the-payment'})"
          >{{ __('Proceed to payment') }}</button>
      </div>
    </div>
  </div>
  {{-- modal --}}
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
    <div class="my-2 grid md:grid-cols-2 gap-x-4">
      <div x-data="detail">
        <div class="rounded-lg">
          <div class="mb-4 grid grid-cols-4 gap-1">
            <template x-for="paymentMethod in paymentMethods">
              <div
                x-on:click="cartDetail['payment_method_id'] = paymentMethod.id; $wire.cartDetail['payment_method_id'] = paymentMethod.id;"
                class="cursor-pointer hover:scale-105 border-none rounded-md px-4 py-2 flex justify-center dark:text-white text-sm"
                :class="cartDetail['payment_method_id']  == paymentMethod.id ? 'bg-lakasir-primary text-white' : 'dark:bg-gray-900 bg-gray-300 '"
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
            class="w-full p-2 border border-gray-300 rounded-md text-lg text-right dark:bg-gray-900 bg-white dark:text-white text-black @error('payed_money') 'border-danger-500' @enderror"
            focus
            :disabled="isTouchScreen"
            x-mask:dynamic="$money($input)"
            x-on:keyup="changes"
            x-ref="payedMoney"
            inputMode="numeric"
          >
          <div class="grid grid-cols-3 gap-4 mt-4" id="calculator-button-shortcut">
          </div>
          <div class="grid grid-cols-3 gap-2 lg:gap-2 mt-2 lg:mt-2" id="calculator-button">
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
            <div class="flex col-span-3 gap-x-2">
              <button
                wire:loading.attr="disabled"
                type="submit" class="w-full bg-lakasir-primary hover:bg-[#ff6611] p-2 rounded-md text-white text-lg flex justify-center items-center gap-x-2">
                <div wire:loading>
                  <x-filament::loading-indicator class="h-5 w-5"/>
                </div>
                {{ __('Pay it') }}
              </button>
              <button
                wire:click="dispatch('close-modal', {id: 'proceed-the-payment'});"
                type="button" class="w-full bg-gray-300 p-2 rounded-md text-lg flex justify-center items-center gap-x-2">
                {{ __('Close') }}
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="overflow-y-scroll max-h-[80vh] hidden md:block">
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
      <p class="">@lang('Success')</p>
      <p class="font-bold text-3xl">
        @lang('Change'):
        <span id="changes"></span>
      </p>
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
  <x-filament::modal
    id="modal-selected-table"
    width="xl"
    :close-by-clicking-away="false"
    :close-by-escaping="false"
    >
    <div class="grid grid-cols-4 gap-4">
      @foreach($tableOption as $table)
      <div
        x-on:click="$wire.cartDetail['table_id'] = {{ $table->id }};"
        class="cursor-pointer hover:scale-105 border border-lakasir-primary rounded-md px-4 py-2 flex justify-center dark:text-white text-sm"
        :class="$wire.cartDetail['table_id']  == {{ $table->id }} ? 'bg-lakasir-primary text-white' : 'dark:bg-gray-900 '"
      >
        {{ $table->number }}
      </div>
      @endforeach
    </div>
    <x-slot name="footer">
      <x-slot name="heading">
        <p id="titleEditDetail">{{ __('Choose the table') }}</p>
      </x-slot>
      <div class="grid grid-cols-2 gap-x-2">
        <x-filament::button id="saveSelectedTable" x-on:click="$dispatch('close-modal', {id: 'modal-selected-table'}); $wire.storeCart()">
          {{ __('Save') }}
        </x-filament::button>
        <x-filament::button color="gray" x-on:click="$dispatch('close-modal', {id: 'modal-selected-table'})">
          {{ __('Close') }}
        </x-filament::button>
      </div>
    </x-slot>
  </x-filament::modal>
</div>

@script()
<script>
  let selling = null;
  $wire.on('selling-created', (event) => {
    selling = event.selling;
    $wire.dispatch('close-modal', {id: 'proceed-the-payment'});

    $wire.dispatch('open-modal', {id: 'success-modal', money_changes: selling.money_changes });
    setTimeout(() => {
      document.getElementById('changes').innerHTML = moneyFormat(selling.money_changes);
    }, 300);
  });
  document.getElementById("printReceiptButton").addEventListener('click', async (event) => {
    let about = @js($about);
    const printerData = getPrinter();

    try {
      if (!printerData) {
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
        const printer = new Printer(printerData.printerId);
        let printerAction = printer.font('a');
        if(about != undefined || about != null) {
          printerAction.size(1)
            .align('center')
            .text(about.shop_name)
            .size(0)
            .text(about.shop_location);
          if(printerData.header != undefined) {
            printerAction
              .text(printerData.header);
          }
          printerAction.align('left')
            .text('-------------------------------');
        }
        printerAction.table(['@lang('Cashier')', selling.user.name])
        if(selling.table != undefined && selling.table != null) {
          printerAction.table(['@lang('Table')', selling.table.number])
        }
        printerAction.table(['@lang('Payment method')', selling.payment_method.name]);
        if(selling.member != undefined && selling.member != null) {
          printerAction
            .table(['Member', selling.member.name]);
        }
        printerAction
          .text('-------------------------------');
        selling.selling_details.forEach(sellingDetail => {
          let price = sellingDetail.price;
          let text = moneyFormat(sellingDetail.price / sellingDetail.qty) + ' x ' + sellingDetail.qty.toString();
          printerAction.table([sellingDetail.product.name, moneyFormat(sellingDetail.price / sellingDetail.qty) + ' x ' + sellingDetail.qty.toString()])
          if (sellingDetail.discount_price > 0) {
            price = price - sellingDetail.discount_price;
            printerAction
              .align('right')
              .text(`(${moneyFormat(sellingDetail.discount_price)})`)
          }
          printerAction
            .align('right')
            .text(moneyFormat(price))
            .align('left')
        });
        printerAction
          .text('-------------------------------');
        if("@js(feature(SellingTax::class))" == 'true') {
          printerAction.table(['@lang('Tax')', `${selling.tax}%`])
            .table(['@lang('Tax price')', moneyFormat(selling.tax_price)]);
        }
        printerAction
          .table(['@lang('Subtotal')', moneyFormat(selling.total_price)])
        if("@js(feature(Discount::class))" == 'true') {
          printerAction
            .table(['@lang('Discount')', `(${moneyFormat(selling.total_discount_per_item + selling.discount_price)})`])
        }
        printerAction
          .table(['@lang('Total price')', moneyFormat(selling.grand_total_price)])
          .text('-------------------------------')
          .table(['@lang('Payed money')', moneyFormat(selling.payed_money)])
          .table(['@lang('Change')', moneyFormat(selling.money_changes)])
          .align('center');

        if(printerData.footer != undefined) {
          printerAction
            .text(printerData.footer);
        }

        await printerAction
          .cut()
          .print();
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
      shortcut(number) {
        this.$refs.payedMoney.value = moneyFormat(number);
        this.changes();
        return;
      },
      append(number) {
        if(number == 'no_changes') {
          this.$refs.payedMoney.value = moneyFormat(this.subtotal);
          this.changes();
          return;
        }
        if(number == 'backspace') {
          this.displayValue = this.displayValue.slice(0, -1);
          this.$refs.payedMoney.value = moneyFormat(this.displayValue);
          this.changes();
          return;
        }
        this.displayValue += number;
        this.$refs.payedMoney.value = moneyFormat(this.displayValue);
        this.changes();
      },
      changes() {
        let num = parseFloat(this.$refs.payedMoney.value.replace(/,/g, ''));
        num = isNaN(num) ? 0 : num;
        $wire.cartDetail['money_changes'] = num - (this.subtotal);
        $wire.cartDetail['payed_money'] = num;
        this.$refs.moneyChanges.textContent = moneyFormat($wire.cartDetail['money_changes']);
      }
    }
  });

  Alpine.data('cart', () => {
    return {
      add: (productId, amount) => {
        $wire.addCart(productId, {amount: amount ?? 0})
        console.log(productId, amount)
      }
    }
  })

  let barcodeData = '';
  let barcodeTimeout;
  let scannerEnabled = true;
  let modalOpened = false;
  let input;
  let index;

  function generateSuggestedPayments(totalPrice) {
    const denominations = [500, 1000, 2000, 5000, 10000, 20000, 50000, 100000];
    const suggestions = [];

    for (let denom of denominations) {
      const suggestion = Math.ceil(totalPrice / denom) * denom;
      if (!suggestions.includes(suggestion)) {
        suggestions.push(suggestion);
      }
    }

    suggestions.sort((a, b) => a - b);

    return suggestions;
  }

  function generateButton(totalPrice) {
    const shortcutSuggestion = generateSuggestedPayments(totalPrice);
    let calculatorBtn = document.getElementById('calculator-button-shortcut');
    calculatorBtn.innerHTML = '';

    for (let suggestion of shortcutSuggestion) {
      const button = document.createElement('button');
      button.textContent = moneyFormat(suggestion);
      button.setAttribute('type', 'button')
      button.setAttribute('x-on:click', `shortcut(${suggestion})`);
      button.className = 'bg-gray-300 hover:bg-gray-400 p-2 rounded-md text-lg';
      calculatorBtn.appendChild(button);
    }
  }

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
    let totalPrice = $refs.total.getAttribute('data-value');
    if("@js(feature(PaymentShortcutButton::class))" == 'true') {
      generateButton(totalPrice);
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
      }, 1000);
    } else {
      barcodeData += event.key;
    }

    barcodeTimeout = setTimeout(() => {
      barcodeData = '';
    }, 500);
  });
</script>
@endscript
