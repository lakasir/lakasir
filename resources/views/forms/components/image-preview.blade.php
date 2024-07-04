<x-dynamic-component
  :component="$getFieldWrapperView()"
  :field="$field">
  <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
    <!-- Interact with the `state` property in Alpine.js -->
    <img :src="state" alt="images" width="200" class="border border-gray-300 dark:border-gray-700 rounded-lg">
  </div>
</x-dynamic-component>
