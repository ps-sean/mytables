<x-app-layout>
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
                    <a href="{{ route("restaurant.show", [$restaurant, $restaurant->name]) }}">
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
</x-app-layout>
