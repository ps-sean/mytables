<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Restaurants') }}
        </h2>
    </x-slot>
    <div class="container mx-auto bg-white py-5 px-3 overflow-auto">
        @foreach(auth()->user()->restaurants as $restaurant)
            <a href="{{ route("restaurant.manage", $restaurant->id) }}">
                <x-restaurant.card class="mb-5" :restaurant="$restaurant">
                    <x-slot name="status">
                        <div class="text-center mx-auto {{ $restaurant->status->color }}">
                            <div class="h-12 inline-block mx-auto">{!! $restaurant->status->icon !!}</div>
                            <p>{{ $restaurant->status->text }}</p>
                        </div>
                    </x-slot>
                </x-restaurant.card>
            </a>
        @endforeach
    </div>
</x-app-layout>
