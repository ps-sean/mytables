<a href="{{ route("booking", $booking) }}">
    <div class="shadow bg-white rounded grid md:grid-cols-4 mb-5">
        <div class="relative h-48 w-full md:h-full flex-none bg-cover bg-center text-center overflow-hidden" style="background-image: url('{{ $booking->restaurant->image }}')" title="{{ $booking->restaurant->name }} logo">
            @if(!empty($booking->restaurant->image_location) && !empty($booking->restaurant->logo_location))
                <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $booking->restaurant->logo }}"/>
            @endif
        </div>
        <div class="md:col-span-2 p-3 space-y-2">
            <p class="text-gray-900 font-bold text-md md:text-xl">{{ $booking->restaurant->name }}</p>
            <p class="flex items-center"><x-icons.calendar class="h-5 inline mr-2"/> {{ $booking->booked_at->toDayDateTimeString() }}</p>
            <p class="flex items-center"><x-icons.user class="h-5 inline mr-2"/> {{ $booking->covers }}</p>
        </div>
        <div class="flex justify-center items-center">
            @switch($booking->status)
                @case("confirmed")
                <p class="text-green-400 flex text-base"><x-icons.check class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
                @break
                @case("rejected")
                @case("cancelled")
                <p class="text-red-600 flex text-base"><x-icons.cross class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
                @break
                @default
                <p class="text-yellow-300 flex text-base"><x-icons.info class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
                @break
            @endswitch
        </div>
    </div>
</a>
