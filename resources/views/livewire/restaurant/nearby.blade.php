<div>
    <h2 class="text-xl">Restaurants Near You</h2>
    @if($restaurants->count())
        <div class="grid md:grid-cols-3 md:gap-3 my-3">
            @foreach($restaurants as $restaurant)
                @if($loop->index < $limit)
                    <div>
                        <a href="{{ route("restaurant.show", $restaurant) }}">
                            <x-restaurant.big-card :restaurant="$restaurant">
                                {{ number_format($restaurant->distance, 2) }} miles away
                            </x-restaurant.big-card>
                        </a>
                    </div>
                @else
                    <div class="col-span-3 text-center my-3">
                        <a class="text-red-800 hover:text-red-700 hover:underline transition-all duration-150 ease-in-out" href="#show-more" wire:click="showMore">&plus; {{ $loop->remaining + 1 }} more</a>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <p class="text-center my-5">
            There aren't any restaurants near you.
        </p>
    @endif
</div>
