<div x-show="open" id="nearby_restaurants" x-data="{ open:true, loading:true }" x-init="() => {
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition((position) => {
            $wire.call('load', position.coords.latitude, position.coords.longitude)
            loading = false
        }, (e) => {
            open = false
            console.log(e)
        }, {
            timeout: 5000,
            enableHighAccuracy: true,
        })
    } else {
        open = false
    }
}">
    @if(collect($restaurants)->count())
        <x-restaurant-group-container>
            <x-slot name="title">Nearby Restaurants</x-slot>
            <p x-show="loading" class="my-5">Loading...</p>
            @foreach($restaurants as $restaurant)
                <div>
                    <a href="{{ route("restaurant.show", [$restaurant, $restaurant->name]) }}">
                        <x-restaurant.big-card :restaurant="$restaurant">
                            {{ number_format($restaurant->distance, 2) }} miles away
                        </x-restaurant.big-card>
                    </a>
                </div>
            @endforeach
            <a class="w-8 h-8 border border-gray-100 shadow-sm flex items-center justify-center" href="#show-more" wire:click="showMore">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </x-restaurant-group-container>
    @endif
</div>
