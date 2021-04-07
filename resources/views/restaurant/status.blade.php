<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Restaurants - {{ ucwords($status) }}
        </h2>
    </x-slot>

    @livewire('restaurant.status.confirm', compact('restaurants'))
</x-app-layout>
