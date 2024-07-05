<x-dynamic-component
  :component="$getFieldWrapperView()"
  :field="$field">
  <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
    <!-- Interact with the `state` property in Alpine.js -->
    <template x-if="state">
      <img :src="state" alt="images" width="200" class="border border-gray-300 dark:border-gray-700 rounded-lg">
    </template>
    <template x-if="!state">
      <p class="text-3xl">@lang('Empty')</p>
    </template>
  </div>
</x-dynamic-component>
