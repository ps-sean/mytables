<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $booking->name }} - {{ $booking->booked_at->toDayDateTimeString() }}
            </h2>

            @if(strtolower($booking->status) === "cancelled")
                <div class="bg-red-600 px-3 py-1 text-white flex items-center rounded">
                    <x-icons.info class="inline mr-2 h-5"/> Cancelled
                </div>
            @endif
        </div>
    </x-slot>

    <div class="container mx-auto bg-white">
        <div class="grid md:grid-cols-2">
            <div class="relative h-64 bg-cover bg-center" style="background-image: url('{{ $restaurant->image }}');">
                @if(!empty($restaurant->image_location) && !empty($restaurant->logo_location))
                    <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $restaurant->logo }}"/>
                @endif
            </div>
            <div class="h-64">
                <iframe class="w-full h-full"
                        frameborder="0" style="border:0"
                        src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.key') }}&q={{ $restaurant->lat }},{{ $restaurant->lng }}"
                        allowfullscreen>
                </iframe>
            </div>
        </div>
        <div class="px-5 py-3 bg-red-800 text-white font-bold">
            Location
        </div>
        <div class="p-5 md:grid md:grid-cols-2 gap-4 space-y-4">
            <p class="text-gray-700 text-2xl font-bold col-span-2">
                {{ $restaurant->name }}
            </p>
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.map-marker class="h-5 inline mr-2"/>
                {{ $restaurant->address }}
            </p>
            <div class="text-gray-700 text-base flex items-center">
                <x-icons.star class="h-5 inline mr-2"/>
                <x-restaurant.reviews :restaurant="$restaurant"/>
            </div>
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.phone class="h-5 inline mr-2"/>
                {{ $restaurant->phone }}
            </p>
            <p class="ext-gray-700 text-base flex items-start">
                <x-icons.at class="h-5 inline mr-2"/>
                {{ $restaurant->email }}
            </p>
            <div class="ext-gray-700 text-base flex items-center">
                <x-icons.clock class="h-5 inline mr-2"/>
                <div>
                    <p class="font-bold">
                        {{ $booking->booked_at->englishDayOfWeek }}'s Opening Hours
                    </p>
                    <p>
                        {{ empty($restaurant->open_hours[$booking->booked_at->shortEnglishDayOfWeek]['open']) || empty($restaurant->open_hours[$booking->booked_at->shortEnglishDayOfWeek]['close']) ? 'Closed' : $restaurant->open_hours[$booking->booked_at->shortEnglishDayOfWeek]['open'] . ' - ' . $restaurant->open_hours[$booking->booked_at->shortEnglishDayOfWeek]['close'] }}
                    </p>
                </div>
            </div>
        </div>
        <div class="px-5 py-3 bg-red-800 text-white font-bold">
            Booking
        </div>
        <div class="grid md:grid-cols-2">
            @livewire("restaurant.booking-details", compact(["booking"]))

            <div class="p-5">
                @if(!empty($booking->booked_by) && !empty($booking->booker->password))
                    @livewire("restaurant.booking-messenger", compact(["booking"]))
                @else
                    <p class="italic">Messenger is not available as the booking was not created by a registered user.</p>
                @endif
            </div>
        </div>

        @auth
            @if(!empty($booking->booked_by) && $booking->booked_by == auth()->user()->id && in_array(strtolower($booking->status), ["confirmed", "seated"]) && $booking->booked_at->isPast())
                <div class="px-5 py-3 bg-red-800 text-white font-bold" id="review">
                    Leave a Review
                </div>
                @livewire("restaurant.review", compact(["booking"]))
            @endif
        @endauth
    </div>
</x-app-layout>
