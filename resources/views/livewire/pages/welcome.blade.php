<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\state;

layout('livewire.components.layouts.guest');

$menu = [
    [
        'title' => 'Analisis Penjualan',
        'description' => 'Menu ini memudahkan anda dalam melihat analisis penjualan yang ada di toko anda.',
        'image' => '/assets/images/dashboard.png',
    ],
    [
        'title' => 'Stok Management',
        'description' => 'Menu ini memudahkan anda dalam mengelola stok barang yang ada di toko anda.',
        'image' => '/assets/images/stock-management.png',
    ],
    [
        'title' => 'Kalulator Pembayaran',
        'description' => 'Di kalkulator pembayaran ini anda bisa menghitung manual seperti kalkulator biasa, namun juga bisa menghitung secara otomatis.',
        'image' => '/assets/images/calculator-payment.png',
    ],
];

$prices = [
    [
        'title' => 'Pribadi',
        'description' => 'Kami menyediakan gratis untuk temen-temen pengguna dengan syarat menggunakan server anda sendiri',
        'price' => 'IDR 0.00',
        'button' => 'Bagaimana caranya?',
        'route' => '',
        'includes' => ['Semua fitur gratis'],
        'excludes' => [
            'Gratis biaya pemasangan', 'Maintenance', 'Dukungan 24 jam', 'Menyediakan backup data', 'Pembaharuan fitur',
        ],
    ],
    [
        'title' => 'Server kami',
        'description' => 'Dengan menggunakan server kami, anda bisa mendapatkan fitur yang lebih lengkap.',
        'price' => 'IDR 50.000',
        'button' => 'Bagaimana caranya?',
        'route' => '',
        'includes' => [
            'Semua fitur gratis', 'Gratis biaya pemasangan', 'Maintenance', 'Dukungan 24 jam', 'Menyediakan backup data', 'Pembaharuan fitur',
        ],
        'excludes' => [],
    ],
];

$mainFeatures = [
    [
        'title' => 'Gratis',
        'description' => 'Bisa menggunakan aplikasi ini secara gratis tanpa dipungut biaya apapun.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
            </svg>',
    ],
    [
        'title' => 'Gunakan Servermu',
        'description' => 'Sepenuhnya bisa di deploy ke servermu sendiri',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 0 0 4.5 4.5H18a3.75 3.75 0 0 0 1.332-7.257 3 3 0 0 0-3.758-3.848 5.25 5.25 0 0 0-10.233 2.33A4.502 4.502 0 0 0 2.25 15Z" />
            </svg>',
    ],
    [
        'title' => 'Multi Platform',
        'description' => 'Lakasir bisa diakses di android dan web',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
            </svg>',
    ],
    [
        'title' => 'Configurable',
        'description' => 'Lakasir bisa di konfigurasi sesuai kebutuhan anda',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>',
    ],
];

state([
    'menu' => $menu,
    'prices' => $prices,
    'mainFeatures' => $mainFeatures,
]);

?>

<div>
  <section class="w-full bg-white lg:h-screen md:h-[80vh]" data-tails-scripts="//unpkg.com/alpinejs">
    <header class="relative block w-full py-6 leading-10 text-center">
      <div class="w-full px-6 mx-auto leading-10 text-center lg:px-8 max-w-7xl">
        <div class="box-border flex flex-wrap items-center justify-between -mx-4 text-indigo-900">
          <div class="relative z-10 flex items-center w-auto px-4 leading-10 lg:flex-grow-0 lg:flex-shrink-0 lg:text-left">
            <a href="/" class="flex box-border font-sans text-2xl font-bold text-left text-gray-900 no-underline bg-transparent cursor-pointer focus:no-underline items-end gap-x-2">
              <img src="{{ env('APP_URL') }}/assets/logo/image.png" class="h-10"> <p>Lakasir</p>
            </a>
          </div>

          <div class="relative items-center hidden px-4 mt-2 space-x-5 font-medium leading-10 md:flex md:flex-grow-0 md:flex-shrink-0 md:mt-0 md:text-right lg:flex-grow-0 lg:flex-shrink-0">
            <a href="https://wa.me/6289638706830?text=Halo%20dengan%20lakasir%2C%20saya%20ingin%20mengatur%20jadwal%20demo%20dengan%20Anda.%20Bisakah%20kita%20membicarakannya%20lebih%20lanjut%3F"
              class="bg-gray-100 text-gray-400 md:w-auto w-full px-8 py-3 rounded-full flex items-center justify-center font-medium text-lg focus:ring-offset-2 focus:ring-2 focus:ring-gray-100 focus:text-gray-700 hover:text-gray-700"
            >Jadwalkan Demo</a>
            <a href="{{ route('auth.register') }}" class="bg-lakasir-primary text-white md:w-auto w-full px-8 py-3 rounded-full flex items-center justify-center font-medium text-lg focus:ring-offset-2 focus:ring-2 focus:ring-lakasir-primary">Daftar</a>
          </div>

          <!-- Sidebar -->
          <div class="md:hidden">
            <div>
              <div>
                <button class="text-white hidden left-10 fixed top-20 text-2xl px-3 py-1 bg-lakasir-primary rounded-lg"
                  x-ref="xButton"
                  x-on:click="$refs.menu.classList.toggle('hidden'); $refs.xButton.classList.toggle('hidden');"
                  >X</button>
              </div>
              <div class="fixed inset-y-0 right-0 w-64 bg-gray-800 text-white flex-col justify-between z-20 hidden" x-ref="menu">
                <!-- Links -->
                <div class="flex flex-col mt-8">
                  <a href="#" class="px-6 py-3 text-sm font-medium">Jadwalkan Demo</a>
                  <a href="{{ route('auth.register') }}" class="px-6 py-3 text-sm font-medium">Daftar</a>
                </div>
              </div>
            </div>
            <!-- Content -->
            <div class="flex items-center justify-center h-full mr-5 text-gray-800" x-on:click="$refs.menu.classList.toggle('hidden'); $refs.xButton.classList.toggle('hidden');">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
            </div>
          </div>
        </div>
      </div>
    </header>
    <div x-ref="overlay" class="fixed hidden z-40 w-screen h-screen inset-0 bg-gray-900 bg-opacity-60"></div>
    <!-- The dialog -->
    <div x-ref="dialog"
      class="hidden fixed z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-auto bg-white rounded-md px-8 py-6 space-y-5 drop-shadow-lg">
      <!--- right Close button -->
      <div class="w-full flex justify-end cursor-pointer" x-on:click="$refs.overlay.classList.add('hidden'); $refs.dialog.classList.add('hidden');">
        <button class="text-gray-600">X</button>
      </div>
      <iframe width="1024" height="576"
        src="https://www.youtube.com/embed/O5rYsoAZ_sk?si=kPhn1AxNqTgQR3Sf&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>


    <main class="w-full relative">
      <div class="max-w-7xl px-10 mx-auto flex lg:flex-row flex-col py-20">
        <div class="w-full lg:w-1/2 flex lg:justify-start justify-start md:justify-center hero-title">
          <div class="lg:py-24  lg:text-left text-left md:text-center">
            <br/>
            <h1 class="mt-4 text-4xl tracking-tight font-extrabold text-gray-800 sm:mt-5 lg:text-left text-left md:text-center sm:text-6xl lg:mt-6 xl:text-7xl">
              <span class="block">Lakasir</span>
              <span class="text-lakasir-primary flex items-center justify-start lg:justify-start md:justify-center w-full">Free POS</span>
            </h1>
            <p class="mt-3 text-base text-gray-400 sm:mt-5 sm:text-xl lg:text-lg  lg:text-left text-left md:text-center xl:text-xl">
              Lakasir merupakan aplikasi point of sale (POS) yang memudahkan pengelolaan bisnis Anda.
              <br class="xl:block hidden"> Lakasir hadir dengan fitur yang lengkap dan mudah digunakan.
            </p>
            <div class="mt-6 sm:mt-8">
              <div class="flex md:flex-row flex-col md:space-x-5 md:space-y-0 space-y-5 lg:justify-start justify-center">
                <a href="https://github.com/lakasir/lakasir_flutter/releases" target="_blank" class="bg-lakasir-primary text-white md:w-auto w-full px-8 py-4 rounded-full flex items-center justify-center font-medium text-lg focus:ring-offset-2 focus:ring-2 focus:ring-lakasir-primary">Unduh Sekarang</a>
                <a href="#_"
                  class="bg-gray-800 text-white px-8 py-4 rounded-full flex items-center justify-center font-medium text-lg focus:ring-offset-2 focus:ring-2 focus:ring-gray-800"
                  x-on:click="$refs.overlay.classList.remove('hidden'); $refs.dialog.classList.remove('hidden');"
                >
                  <span class="-ml-2 mr-3"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg></span>
                  <span>Tonton Video</span>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full lg:w-1/2 lg:flex lg:justify-center lg:max-w-none max-w-md lg:mt-0 mt-20 mx-auto relative hidden hero-phone">
          <div class="w-auto sm:w-64 absolute bottom-0 transform md:bottom-auto md:top-1/2 p-8 translate-y-16 md:translate-y-24 md:ml-16 z-10 left-0 bg-white text-gray-400 rounded-xl shadow-2xl hidden md:block">
            <div class="inline-flex absolute top-0 transform -translate-y-full bg-lakasir-primary left-0  space-x-1 px-4 items-center h-9 w-auto rounded-full -mt-2">

              <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 534 509" xmlns="http://www.w3.org/2000/svg"><path d="m409.8 313.24 114.8-94.637c16.238-13.441 7.84-39.762-13.441-40.879l-147.84-8.96c-8.96-.56-16.801-6.161-20.16-14.56l-54.32-138.88c-7.84-19.602-35.281-19.602-43.121 0l-54.32 138.32c-3.36 8.399-11.199 14-20.16 14.56l-148.4 8.96c-21.281 1.121-29.68 27.441-13.441 40.879l114.8 94.078c6.719 5.602 10.078 15.121 7.84 23.52l-37.52 143.92c-5.04 20.16 16.8 36.398 34.719 25.199l124.88-80.078c7.84-5.04 17.359-5.04 24.64 0l125.44 80.078c17.923 11.199 39.763-5.04 34.72-25.199l-37.52-143.36c-1.68-8.398 1.12-17.359 8.402-22.961h.002Z" fill-rule="nonzero"/></svg>
              <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 534 509" xmlns="http://www.w3.org/2000/svg"><path d="m409.8 313.24 114.8-94.637c16.238-13.441 7.84-39.762-13.441-40.879l-147.84-8.96c-8.96-.56-16.801-6.161-20.16-14.56l-54.32-138.88c-7.84-19.602-35.281-19.602-43.121 0l-54.32 138.32c-3.36 8.399-11.199 14-20.16 14.56l-148.4 8.96c-21.281 1.121-29.68 27.441-13.441 40.879l114.8 94.078c6.719 5.602 10.078 15.121 7.84 23.52l-37.52 143.92c-5.04 20.16 16.8 36.398 34.719 25.199l124.88-80.078c7.84-5.04 17.359-5.04 24.64 0l125.44 80.078c17.923 11.199 39.763-5.04 34.72-25.199l-37.52-143.36c-1.68-8.398 1.12-17.359 8.402-22.961h.002Z" fill-rule="nonzero"/></svg>
              <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 534 509" xmlns="http://www.w3.org/2000/svg"><path d="m409.8 313.24 114.8-94.637c16.238-13.441 7.84-39.762-13.441-40.879l-147.84-8.96c-8.96-.56-16.801-6.161-20.16-14.56l-54.32-138.88c-7.84-19.602-35.281-19.602-43.121 0l-54.32 138.32c-3.36 8.399-11.199 14-20.16 14.56l-148.4 8.96c-21.281 1.121-29.68 27.441-13.441 40.879l114.8 94.078c6.719 5.602 10.078 15.121 7.84 23.52l-37.52 143.92c-5.04 20.16 16.8 36.398 34.719 25.199l124.88-80.078c7.84-5.04 17.359-5.04 24.64 0l125.44 80.078c17.923 11.199 39.763-5.04 34.72-25.199l-37.52-143.36c-1.68-8.398 1.12-17.359 8.402-22.961h.002Z" fill-rule="nonzero"/></svg>
              <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 534 509" xmlns="http://www.w3.org/2000/svg"><path d="m409.8 313.24 114.8-94.637c16.238-13.441 7.84-39.762-13.441-40.879l-147.84-8.96c-8.96-.56-16.801-6.161-20.16-14.56l-54.32-138.88c-7.84-19.602-35.281-19.602-43.121 0l-54.32 138.32c-3.36 8.399-11.199 14-20.16 14.56l-148.4 8.96c-21.281 1.121-29.68 27.441-13.441 40.879l114.8 94.078c6.719 5.602 10.078 15.121 7.84 23.52l-37.52 143.92c-5.04 20.16 16.8 36.398 34.719 25.199l124.88-80.078c7.84-5.04 17.359-5.04 24.64 0l125.44 80.078c17.923 11.199 39.763-5.04 34.72-25.199l-37.52-143.36c-1.68-8.398 1.12-17.359 8.402-22.961h.002Z" fill-rule="nonzero"/></svg>
              <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 534 509" xmlns="http://www.w3.org/2000/svg"><path d="m409.8 313.24 114.8-94.637c16.238-13.441 7.84-39.762-13.441-40.879l-147.84-8.96c-8.96-.56-16.801-6.161-20.16-14.56l-54.32-138.88c-7.84-19.602-35.281-19.602-43.121 0l-54.32 138.32c-3.36 8.399-11.199 14-20.16 14.56l-148.4 8.96c-21.281 1.121-29.68 27.441-13.441 40.879l114.8 94.078c6.719 5.602 10.078 15.121 7.84 23.52l-37.52 143.92c-5.04 20.16 16.8 36.398 34.719 25.199l124.88-80.078c7.84-5.04 17.359-5.04 24.64 0l125.44 80.078c17.923 11.199 39.763-5.04 34.72-25.199l-37.52-143.36c-1.68-8.398 1.12-17.359 8.402-22.961h.002Z" fill-rule="nonzero"/></svg>
            </div>
            <p class="text-gray-800 font-bold">Anonim</p>
            <p class="mt-2">Lakasir memudahkan saya dalam mengelola bisnis saya, terimakasih Lakasir.</p>
          </div>
          <div class="w-full flex items-end max-w-md h-auto relative">

            <img src="/assets/images/cashier-transaction-1.png" class="relative h-[40rem] lg:left-20">
          </div>
        </div>
      </div>
    </main>
  </section>
  <section id="about" class="bg-gray-800 text-white py-32">
    <div class="lg:max-w-3xl mx-auto text-center px-5 lg:px-0">
      <p class="text-4xl font-extrabold">Tentang Lakasir</p>
      <p class="text-lg mt-5">Lakasir adalah aplikasi POS open-source dan juga gratis, lakasir tersedia untuk android dan web, dengan fitur yang dimiliki lakasir sekarang lakasir bisa memudahkan anda dalam mencatat keuntungan anda perhari dengan mudah, lakasir juga bisa membantu anda dalam mengelola barang yang ada di toko anda, dengan fitur yang lengkap dan mudah digunakan.</p>
    </div>
  </section>
  <section id="product-menu" class="py-10 my-10 text-gray-400">
    <div class="xl:max-w-7xl lg:max-w-4xl mx-auto">
      <p class="text-4xl font-extrabold text-center text-gray-800">Menu Lakasir</p>
      <div class="lg:grid lg:grid-cols-3 grid-cols-2 my-10 gap-x-5">
        @foreach ($menu as $item)
          @if ($loop->iteration % 2 == 0)
            <div class="grid grid-cols-2 lg:grid-cols-none lg:block my-10 md:my-0">
              <div class="flex gap-x-5 justify-items-center lg:mb-10 sm:my-auto ml-10 lg:ml-0">
                <div class="h-10 min-w-2 bg-lakasir-primary rounded-lg"></div>
                <div class="lg:grid lg:grid-cols-1 xl:gap-y-3 lg:gap-y-1">
                  <p class="text-2xl text-gray-800 font-bold">{{ $item['title'] }}</p>
                  <p class="text-gray-400 w-4/5">{{ $item['description'] }}</p>
                </div>
              </div>
              <div class="flex justify-center">
                <img src="{{ $item['image'] }}" class="w-44">
              </div>
            </div>
          @else
            <div class="grid grid-cols-2 lg:grid-cols-none lg:block">
              <div class="flex justify-center">
                <img src="{{ $item['image'] }}" class="w-44">
              </div>
              <div class="flex gap-x-5 justify-items-center lg:mt-10 sm:my-auto">
                <div class="h-10 min-w-2 bg-lakasir-primary rounded-lg"></div>
                <div class="lg:grid lg:grid-cols-1 xl:gap-y-3 lg:gap-y-1">
                  <p class="text-2xl text-gray-800 font-bold">{{ $item['title'] }}</p>
                  <p class="text-gray-400 w-4/5">{{ $item['description'] }}</p>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    </div>
  </section>
  <section id="main-feature" class="bg-gray-800 text-white py-32">
    <div class="md:max-w-4xl max-w-1xl mx-auto">
      <div class="mb-20 grid grid-cols-1 gap-y-5">
        <p class="text-4xl font-extrabold text-center">Fitur Lakasir</p>
        <p class="text-lg text-center sm:mx-0 mx-5">Dengan lakasir anda bisa mengelola toko anda dengan mudah, berikut fitur yang dimiliki oleh lakasir.</p>
      </div>
      <div class="grid sm:grid-cols-2 lg:gap-20 gap-10 px-10">
        @foreach($mainFeatures as $feature)
        <div class="flex gap-x-4">
          <div class="bg-lakasir-primary rounded-2xl flex justify-center items-center min-w-10 h-10">
            {!! $feature['icon'] !!}
          </div>
          <div>
            <p class="text-2xl font-bold">{{ $feature['title'] }}</p>
            <p class="text-gray-400 lg:w-4/5">{{ $feature['description'] }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  <section id="price">
    <div class="mx-auto max-w-7xl py-24 px-6 lg:px-8">
      <div class="sm:align-center sm:flex sm:flex-col">
        <p class="text-4xl font-extrabold text-center">Harga dari kami.</p>
        <p class="mt-5 text-xl text-gray-500 sm:text-center"></p>
      </div>
      <div class="mt-12 space-y-4 sm:mt-16 sm:grid sm:grid-cols-2 sm:gap-6 sm:space-y-0 mx-auto max-w-4xl xl:mx-0 xl:max-w-none justify-items-center">
        @foreach($prices as $price)
        <div class="divide-y divide-gray-200 rounded-lg border border-gray-200 shadow-sm w-80">
          <div class="p-6">
            <p class="text-lg font-medium leading-6 text-gray-900">{{ $price['title'] }}</p>
            <p class="mt-4 text-sm text-gray-500">{{ $price['description'] }}</p>
            <p class="mt-8">
              <span class="text-4xl font-bold tracking-tight text-gray-900">{{ $price['price'] }}</span> <span class="text-base font-medium text-gray-500">/bulan</span>
            </p>
            <a href="https://trakteer.id/sheenazien8"
              class="mt-8 block w-full rounded-md border border-gray-800 bg-gray-800 py-2 text-center text-sm font-semibold text-white hover:bg-gray-900">
              {{ $price['button'] }}
            </a>
          </div>
          <div class="px-6 pt-6 pb-8">
            <h3 class="text-sm font-medium text-gray-900">Termasuk</h3>
            <ul role="list" class="mt-6 space-y-4">
              @foreach($price['includes'] as $include)
              <li class="flex space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5 flex-shrink-0 text-green-500">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd">
                  </path>
                </svg>
                <span class="text-sm text-gray-500">{{ $include }}</span>
              </li>
              @endforeach
              @foreach($price['excludes'] as $exclude)
              <li class="flex space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 flex-shrink-0 text-red-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>

                <span class="text-sm text-gray-500">{{ $exclude }}</span>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  <section id="cta" class="xl:max-w-7xl lg:max-w-3xl md:px-10 mx-auto text-white">
    <div class="bg-lakasir-primary text-center rounded-2xl my-20 p-10">
      <p class="font-extrabold text-3xl">Dapatkan pemberitahuan ketika ada pembaharuan dari kami</p>
      <p class="mt-5">Jangan lewatkan update terbaru dari kami, daftarkan email anda sekarang.</p>
      <form action="#" class="mt-5 sm:mx-auto sm:flex sm:max-w-lg">
        <div class="min-w-0 flex-1">
          <label for="cta-email" class="sr-only">Alamat email</label>
          <input id="cta-email" type="email" class="block w-full rounded-md border border-transparent px-5 py-3 text-base text-gray-900 placeholder-gray-500 shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-lakasir-primary" placeholder="Enter your email">
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-3">
          <button type="submit" class="block w-full rounded-md border border-transparent bg-orange-600 px-5 py-3 text-base font-medium text-white shadow hover:bg-lakasir-primary focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-lakasir-primary sm:px-10">Notify me</button>
        </div>
      </form>
    </div>
  </section>
  <section id="footer" class="bg-gray-800 text-white py-10 flex justify-center gap-x-3">
    <p>Lakasir made with </p>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
      <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
    </svg>
  </section>
</div>
