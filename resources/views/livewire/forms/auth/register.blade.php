<div>
  <div class="max-w-2xl mx-auto grid grid-cols-1 justify-center items-center h-screen">
    <div class="space-y-6">
      <div class="flex justify-center items-center">
        <img src="{{ asset('assets/logo/image.png') }}" class="w-20 h-24" alt="Logo">
      </div>
      <div class="w-full">
        <form>
          {{ $this->form }}
        </form>
        <x-filament-actions::modals />
      </div>
    </div>
  </div>
</div>
