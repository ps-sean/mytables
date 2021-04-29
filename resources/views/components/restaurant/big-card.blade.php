<div {{ $attributes->merge(["class" => "rounded w-full shadow hover:shadow-lg transition-shadow duration-150 ease-in-out overflow-hidden"]) }}>
    <div class="relative h-48 w-full flex-none bg-cover bg-center text-center overflow-hidden" style="background-image: url('{{ $restaurant->image }}')" title="{{ $restaurant->name }} logo">
        @if(!empty($restaurant->image_location) && !empty($restaurant->logo_location))
            <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $restaurant->logo }}"/>
        @endif
    </div>
    <div class="p-3 space-y-3">
        <h5 class="text-lg font-bold">{{ $restaurant->name }}</h5>
        <p class="text-gray-700 text-sm md:text-base flex items-start">
            <x-icons.map-marker class="h-5 inline mr-2"/>
            {{ $restaurant->address }}
        </p>
        <p class="text-gray-700 text-sm md:text-base flex items-start">
            <x-icons.phone class="h-5 inline mr-2"/>
            <a class="text-red-800 hover:text-red-700 transition-all duration-150 ease-in-out" href="tel:{{ $restaurant->phone }}">{{ $restaurant->phone }}</a>
        </p>
        <p class="text-gray-700 text-sm md:text-base flex items-start">
            <x-icons.at class="h-5 inline mr-2"/>
            <a class="text-red-800 hover:text-red-700 transition-all duration-150 ease-in-out" href="mailto:{{ $restaurant->email }}">{{ $restaurant->email }}</a>
        </p>
        <div class="text-gray-700 text-base flex items-center">
            <x-icons.star class="h-5 inline mr-2"/>
            <x-restaurant.reviews :restaurant="$restaurant"/>
        </div>
        <p class="italic">
            {{ $slot }}
        </p>
    </div>
</div>
