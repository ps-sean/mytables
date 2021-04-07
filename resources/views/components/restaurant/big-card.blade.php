<div {{ $attributes->merge(["class" => "rounded w-full shadow hover:shadow-lg transition-shadow duration-150 ease-in-out overflow-hidden"]) }}>
    <div class="h-48 w-full flex-none bg-cover bg-center text-center overflow-hidden" style="background-image: url('{{ $restaurant->image }}')" title="{{ $restaurant->name }} logo">
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
        <p class="italic">
            {{ $slot }}
        </p>
    </div>
</div>
