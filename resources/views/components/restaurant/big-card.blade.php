<div {{ $attributes->merge(["class" => "rounded h-full w-64 shadow hover:shadow-lg transition-shadow duration-150 ease-in-out overflow-hidden border border-gray-100"]) }}>
    <div class="relative h-32 w-full flex-none bg-cover bg-center text-center overflow-hidden" style="background-image: url('{{ $restaurant->image }}')" title="{{ $restaurant->name }} logo">
        @if(!empty($restaurant->image_location) && !empty($restaurant->logo_location))
            <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $restaurant->logo }}"/>
        @endif
    </div>
    <div class="p-3 space-y-1">
        <h5 class="text-md font-bold">{{ $restaurant->name }}</h5>
        <div class="text-gray-700 text-sm flex items-center">
            <x-icons.star class="h-5 inline mr-2"/>
            <x-restaurant.reviews :restaurant="$restaurant"/>
        </div>
        <p class="text-gray-700 text-xs md:text-base flex items-start">
            <x-icons.map-marker class="h-5 inline mr-2"/>
            {{ $restaurant->address }}
        </p>
        <p class="text-gray-700 italic text-xs">
            {{ $slot }}
        </p>
    </div>
</div>
