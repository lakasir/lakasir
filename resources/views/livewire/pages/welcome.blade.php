<?php

use function Livewire\Volt\layout;

layout('livewire.components.layouts.guest');

?>

<div>
  <section class="w-full bg-white" data-tails-scripts="//unpkg.com/alpinejs">
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
            <a href="https://drive.usercontent.google.com/download?id=1U5cXfzDOARKhWu6TRzNDQ6zMqFdsPJVb&export=download&authuser=0" target="_blank" class="bg-primary text-white md:w-auto w-full px-8 py-3 rounded-full flex items-center justify-center font-medium text-lg focus:ring-offset-2 focus:ring-2 focus:ring-primary">Unduh</a>
          </div>

          <!-- Mobile Button -->
        </div>
      </div>
    </header>
    <!-- Overlay element -->
    <div x-ref="overlay" class="fixed hidden z-40 w-screen h-screen inset-0 bg-gray-900 bg-opacity-60"></div>

    <!-- The dialog -->
    <div x-ref="dialog"
      class="hidden fixed z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-auto bg-white rounded-md px-8 py-6 space-y-5 drop-shadow-lg">
      <!--- right Close button -->
      <div class="w-full flex justify-end" x-on:click="$refs.overlay.classList.add('hidden'); $refs.dialog.classList.add('hidden');">
        <button class="text-gray-600">X</button>
      </div>
      <iframe width="1024" height="576"
        src="https://www.youtube.com/embed/O5rYsoAZ_sk?si=kPhn1AxNqTgQR3Sf&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>


    <main class="w-full relative">
      <div class="max-w-7xl px-10 mx-auto flex lg:flex-row flex-col py-20">
        <div class="w-full lg:w-1/2 flex lg:justify-start justify-start md:justify-center">
          <div class="lg:py-24  lg:text-left text-left md:text-center">
            <br/>
            <h1 class="mt-4 text-4xl tracking-tight font-extrabold text-gray-800 sm:mt-5 lg:text-left text-left md:text-center sm:text-6xl lg:mt-6 xl:text-7xl">
              <span class="block">Lakasir</span>
              <span class="text-primary flex items-center justify-start lg:justify-start md:justify-center w-full">Free POS</span>
            </h1>
            <p class="mt-3 text-base text-gray-400 sm:mt-5 sm:text-xl lg:text-lg  lg:text-left text-left md:text-center xl:text-xl">
              Lakasir merupakan aplikasi point of sale (POS) yang memudahkan pengelolaan bisnis Anda.
              <br class="xl:block hidden"> Lakasir hadir dengan fitur yang lengkap dan mudah digunakan.
            </p>
            <div class="mt-6 sm:mt-8">
              <div class="flex md:flex-row flex-col md:space-x-5 md:space-y-0 space-y-5 lg:justify-start justify-center">
                <a href="https://drive.usercontent.google.com/download?id=1U5cXfzDOARKhWu6TRzNDQ6zMqFdsPJVb&export=download&authuser=0" target="_blank" class="bg-primary text-white md:w-auto w-full px-8 py-4 rounded-full flex items-center justify-center font-medium text-lg focus:ring-offset-2 focus:ring-2 focus:ring-primary">Unduh Sekarang</a>
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
        <div class="w-full lg:w-1/2 flex lg:justify-center lg:max-w-none max-w-md lg:mt-0 mt-20 mx-auto relative">
          <div class="w-auto sm:w-64 absolute bottom-0 transform md:bottom-auto md:top-1/2 p-8 translate-y-16 md:translate-y-24 md:ml-16 z-10 left-0 bg-white text-gray-400 rounded-xl shadow-2xl hidden md:block">
            <div class="inline-flex absolute top-0 transform -translate-y-full bg-primary left-0  space-x-1 px-4 items-center h-9 w-auto rounded-full -mt-2">

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
      <svg class="w-full absolute sm:block hidden fill-current text-gray-800 bottom-0 left-0 -mb-1" viewBox="0 0 1370 65" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0c243.727 42.976 472.06 64.464 685 64.464S1126.273 42.976 1370 0v64.464H0V0z" fill-rule="evenodd"></path>
      </svg>
    </main>


    <!-- Logos -->
    <div class="bg-gray-800 sm:mt-0 mt-20">
      <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5">
        </div>
      </div>
    </div>

  </section>
</div>
