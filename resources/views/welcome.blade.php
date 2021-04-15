<x-app-layout>
    @if(isset($_GET['search']))
        <div class="relative bg-white shadow z-10">
            <form method="GET" action="/">
                <div class="flex justify-between">
                    <div class="w-10/12">
                        <x-jet-input class="w-full" type="text" placeholder="Search by restaurant name, city or postcode" name="search" value="{{ $_GET['search'] }}"/>
                    </div>
                    <x-button type="submit" class="w-2/12 bg-red-800 hover:bg-red-700 text-white justify-center rounded-l-none rounded-r-none">
                        <svg class="h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="hidden md:inline">Search</span>
                    </x-button>
                </div>
                <div class="text-right">
                    <div>
                        <label>Distance: </label>
                        <x-select name="distance" onchange="this.closest('form').submit();">
                            <option value="5" {{ empty($_GET['distance']) || $_GET['distance'] == 5 ? 'selected': '' }}><5 Miles</option>
                            <option value="10" {{ !empty($_GET['distance']) && $_GET['distance'] == 10 ? 'selected': '' }}><10 Miles</option>
                            <option value="25" {{ !empty($_GET['distance']) && $_GET['distance'] == 25 ? 'selected': '' }}><25 Miles</option>
                            <option value="50" {{ !empty($_GET['distance']) && $_GET['distance'] == 50 ? 'selected': '' }}><50 Miles</option>
                            <option value="100" {{ !empty($_GET['distance']) && $_GET['distance'] == 100 ? 'selected': '' }}><100 Miles</option>
                        </x-select>
                    </div>
                </div>
            </form>
        </div>

        <div class="container mx-auto bg-white p-5">
            @if($restaurants->count())
                @foreach($restaurants as $restaurant)
                    <div class="mb-5">
                        <a href="{{ route("restaurant.show", $restaurant) }}">
                            <x-restaurant.card :restaurant="$restaurant">
                                <x-slot name="status">
                                    <div class="text-gray-700 text-base flex items-center">
                                        <x-icons.star class="h-5 inline mr-2"/>
                                        <x-restaurant.reviews :restaurant="$restaurant" :clicking="false"/>
                                    </div>
                                </x-slot>
                            </x-restaurant.card>
                        </a>
                    </div>
                @endforeach

                {{ $restaurants->appends($_GET)->links() }}
            @else
                <p class="text-center py-5">No locations found for "{{ $_GET['search'] }}"</p>
            @endif
        </div>
    @else
        <div class="bg-top bg-cover" style="background-image: url('{{ asset("img/background.jpg") }}')">
            <div class="bg-gray-800 bg-opacity-75 py-20 px-5 sm:px-10 md:px-32 lg:py-32 lg:px-48 xl:py-48 xl:px-64">
                <h2 class="text-lg text-gray-200 font-bold ml-3">Search for a local restaurant</h2>
                <div class="bg-white rounded-full overflow-hidden">
                    <form class="flex justify-between" method="GET" action="/">
                        <x-jet-input class="w-full" type="text" placeholder="Restaurant name, city or postcode" name="search"/>
                        <x-button type="submit" class="w-2/12 bg-red-800 hover:bg-red-700 text-white justify-center rounded-full">
                            <svg class="h-4 md:mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="hidden md:inline">Search</span>
                        </x-button>
                    </form>
                </div>
            </div>
        </div>

    <div class="-space-y-5">
        @auth
            <div class="container mx-auto p-5">
                <h2 class="text-xl">Favourite Restaurants</h2>
                @if(($restaurants = auth()->user()->top3restaurants()) && $restaurants->count())
                    <div class="grid md:grid-cols-3 md:gap-3 my-3">
                        @foreach($restaurants as $restaurant)
                            @php
                                $counter = $restaurant->timesBooked . " times";

                                if($restaurant->timesBooked === 1){
                                    $counter = "once";
                                } elseif ($restaurant->timesBooked === 2){
                                    $counter = "twice";
                                }
                            @endphp
                            <div>
                                <a href="{{ route("restaurant.show", $restaurant) }}">
                                    <x-restaurant.big-card :restaurant="$restaurant">
                                        Booked {{ $counter }} in the last year
                                    </x-restaurant.big-card>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center my-5">
                        You haven't booked any restaurants yet.
                    </p>
                @endif
            </div>
        @endauth
        <div class="container mx-auto p-5">
            @livewire("restaurant.nearby", [request()->ip()])
        </div>
    </div>
    @endif
</x-app-layout>
