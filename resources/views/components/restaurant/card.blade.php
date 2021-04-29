<div {{ $attributes->merge(["class" => "rounded w-full shadow hover:shadow-lg transition-shadow duration-150 ease-in-out grid md:grid-cols-4"]) }}>
    <div class="relative h-48 w-full md:h-full flex-none bg-cover bg-center text-center overflow-hidden" style="background-image: url('{{ $restaurant->image }}')" title="{{ $restaurant->name }} logo">
        @if(!empty($restaurant->image_location) && !empty($restaurant->logo_location))
            <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $restaurant->logo }}"/>
        @endif
    </div>
    <div class="md:col-span-3 grid grid-cols-4">
        <div class="{{ isset($status) ? 'col-span-3' : 'col-span-4' }} p-3 space-y-2">
            <p class="text-gray-900 font-bold text-md md:text-xl">{{ $restaurant->name }}</p>
            <p class="text-gray-700 text-sm md:text-base flex items-start">
                <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $restaurant->address }}
            </p>
            <div class="grid lg:grid-cols-2">
                <p class="text-gray-700 text-sm md:text-base flex items-start">
                    <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ $restaurant->phone }}
                </p>
                <p class="text-gray-700 text-sm md:text-base flex items-start">
                    <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                    {{ $restaurant->email }}
                </p>
            </div>
            @auth
                @if($restaurant->myRestaurant())
                    <div class="flex items-center">
                        <div class="text-sm">
                            <p class="text-gray-900 leading-none">{{ $restaurant->staff->count() }} staff {{ \Illuminate\Support\Str::plural("member", $restaurant->staff->count()) }}</p>
                            <p class="text-gray-600">{{ $restaurant->created_at->toFormattedDateString() }}</p>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
        @if(isset($status))
            <div class="h-full flex content-center flex-wrap">
                {{ $status }}
            </div>
        @endif
    </div>
</div>
