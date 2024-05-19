@php
use Filament\Facades\Filament;
use function Filament\Support\format_money;

@endphp
<div class="">
  <div class="grid grid-cols-3 gap-x-4">
    <div class="col-span-2">
      {{ $this->table }}
    </div>
    <div class="px-4 mt-4 space-y-2 fixed right-0 w-1/3 h-screen">
      <div class="">
        <p class="text-2xl font-bold">{{ __('Orders details') }}</p>
      </div>
      <div class="flex justify-between">
        <p class="">{{ Filament::auth()->user()->cashier_name }}</p>
        <p class="text-primary">Order numbers: <span class="!text-[#ff6600] font-bold">#0921033</span></p>
      </div>
      <div class="flex justify-between items-center">
        <p class="text-2xl font-bold mb-2">{{ __('Current Orders') }}</p>
        <div class="flex gap-x-1">
          <a
            href="/member/sellings"
            class="py-1 px-4 flex justify-center items-center bg-gray-100 rounded-lg">
            <x-heroicon-o-arrow-left class="h-4 w-4 text-gray-500"/>
          </a>
          <button
            class="py-1 px-4 bg-[#ff6600] text-white rounded-lg"
            x-on:click="$dispatch('open-modal', {id: 'edit-detail'})"
            >{{ __('Edit Detail') }}</button>
          <button class="py-1 px-4 bg-red-200 text-red-500 rounded-lg"
            wire:confirm="Are you sure you want to clear all of the items?"
            wire:click.prevent="clearCart" >
            <x-heroicon-o-trash class="h-4 w-4 text-red-500"/>
          </button>
        </div>
      </div>
      <div class="overflow-y-scroll min-h-40 max-h-[35%] overflow-auto"
        @forelse($cartItems as $item)
          <div class="flex justify-between mb-2 border rounded-lg bg-white dark:border-gray-900 dark:bg-gray-900 px-4 py-2">
            <div class="flex items-center space-x-3">
              <img
              class="object-cover h-16 w-20 rounded-lg"
              src=" {{ $item->product?->hero_images ? $item->product?->hero_images[0] : 'https://cdn4.iconfinder.com/data/icons/picture-sharing-sites/32/No_Image-1024.png' }}"/>
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
            <div class="flex items-center">
              <p class="font-semibold text-[#ff6600]">{{ $item->price_format_money }}</p>
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
        x-on:click="$dispatch('open-modal', {id: 'proceed-the-payment'})"
        >{{ __('Proceed to payment') }}</button>
    </div>
  </div>
  <x-filament::modal
    id="edit-detail"
    width="2xl">
    <form wire:submit.prevent="storeCart">
      <x-slot name="heading">
        {{ __('Edit detail') }}
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
      <x-slot name="heading">
        {{ __('Proceed The Payment') }}
      </x-slot>
    <div class="my-2 grid grid-cols-2 gap-x-4">
      <div x-data="{
         displayValue: '',
         paymentMethods: $wire.entangle('paymentMethods'),
         cartDetail: @js($cartDetail),
         subtotal: $wire.entangle('total_price'),
         append(number) {
           if(number == 'no_changes') {
             $refs.payedMoneyLabel.textContent = this.moneyFormat(this.subtotal);
             $refs.payedMoney.value = this.subtotal;
             this.changes();
             return;
           }
           if(number == 'backspace') {
             this.displayValue = this.displayValue.slice(0, -1);
             $refs.payedMoneyLabel.textContent = this.moneyFormat(this.displayValue);
             $refs.payedMoney.value = this.displayValue;
             this.changes();
             return;
           }
           this.displayValue += number;
           $refs.payedMoneyLabel.textContent = this.moneyFormat(this.displayValue);
           $refs.payedMoney.value = this.displayValue;
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
           $wire.cartDetail['money_changes'] = $refs.payedMoney.value - (this.subtotal);
           $wire.cartDetail['payed_money'] = Number($refs.payedMoney.value);
           $refs.moneyChanges.textContent = this.moneyFormat($wire.cartDetail['money_changes']);
         }
        }">
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
            x-show="paymentMethods.filter((pm) => pm.is_credit)[0].id == cartDetail['payment_method_id']"
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
          <div
            x-ref="payedMoneyLabel"
            class="w-full p-2 border border-gray-300 rounded-md text-lg text-right dark:bg-gray-900 dark:text-white h-20 text-black"
            :class="@error('payed_money') 'border-danger-500' @enderror"
          >
          </div>
          @error('payed_money') <span class="error text-danger-500">{{ $message }}</span> @enderror
          <input
            type="hidden"
            id="display"
            class="w-full mb-4 p-2 border border-gray-300 rounded-md text-lg text-right dark:bg-gray-900 dark:text-white"
            x-ref="payedMoney"
          >
          <div class="grid grid-cols-3 gap-4 mt-4">
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
              wire:target="proceedThePayment"
              type="submit" class="col-span-3 bg-[#ff6600] hover:bg-[#ff6611] p-2 rounded-md text-white text-lg">Pay it</button>
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
</div>

