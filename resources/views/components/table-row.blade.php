<tr {{ $attributes->merge(['class' =>  isset($loop) ? $loop->odd ? 'bg-gray-50 dark:bg-gray-700' : '' : '' ]) }}>
  {{ $slot }}
</tr>
