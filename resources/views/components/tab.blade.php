@props(['key', 'value'])

<button
    @click.prevent="{{ $key }} = '{{ $value }}'"
    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none"
    :class="{{ $key }} === '{{ $value }}' ? 'border-red-800 text-gray-900 focus:border-red-900 text-gray-500 hover:text-gray-700 hover:border-gray-300' : 'border-transparent focus:text-gray-700 focus:border-gray-300'"
    role="tab"
>
    {{ $slot }}
</button>
