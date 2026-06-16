<x-app-layout>
    <div class="bg-top bg-cover" style="background-image: url('{{ asset("img/background.jpg") }}')">
        <div class="bg-gray-800/75 py-20 px-5 sm:px-10 md:px-32 lg:py-32 lg:px-48 xl:py-48 xl:px-64">
            <h2 class="text-lg text-gray-200 font-bold ml-3">Search for a local restaurant</h2>
            <div class="bg-white rounded-full overflow-hidden">
                <form class="flex justify-between" method="GET" action="/">
                    <x-input class="w-full" type="text" placeholder="Restaurant name, city or postcode" name="search"/>
                    <x-pill-button type="submit" class="w-2/12 bg-red-800 hover:bg-red-700 text-white justify-center">
                        <svg class="h-4 md:mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="hidden md:inline">Search</span>
                    </x-pill-button>
                </form>
            </div>
        </div>
    </div>

    @if($favouriteRestaurants->count())
        <x-restaurant-group-container>
            <x-slot name="title">Favourite Restaurants</x-slot>
            @foreach($favouriteRestaurants as $restaurant)
                @php
                    $counter = $restaurant->bookings_count . " times";

                    if($restaurant->bookings_count === 1){
                        $counter = "once";
                    } elseif ($restaurant->bookings_count === 2){
                        $counter = "twice";
                    }
                @endphp
                <div>
                    <a href="{{ route("restaurant.show", [$restaurant, $restaurant->name]) }}">
                        <x-restaurant.big-card :restaurant="$restaurant">
                            Booked {{ $counter }} in the last year
                        </x-restaurant.big-card>
                    </a>
                </div>
            @endforeach
        </x-restaurant-group-container>
    @endif

    @livewire("restaurant.nearby", [request()->ip()])

    <x-restaurant-group-container>
        <x-slot name="title">Popular Restaurants</x-slot>
        @foreach($restaurants as $restaurant)
            <div>
                <a href="{{ route("restaurant.show", [$restaurant, $restaurant->name]) }}">
                    <x-restaurant.big-card :restaurant="$restaurant" />
                </a>
            </div>
        @endforeach
    </x-restaurant-group-container>
</x-app-layout>
