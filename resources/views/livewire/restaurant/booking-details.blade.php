<div class="p-5">
    <form class="p-5 md:grid md:grid-cols-2 gap-4 space-y-4" wire:submit="submit">
        <p class="text-gray-700 text-2xl font-bold col-span-2">
            {{ $booking->name }}
        </p>
        @if(!empty($booking->contact_number))
            <p class="text-gray-700 text-base flex items-start col-span-2">
                <x-icons.phone class="h-5 inline mr-2"/>
                <a href="tel:{{ $booking->contact_number }}">{{ $booking->contact_number }}</a>
            </p>
        @endif
        @if(!empty($booking->email))
            <p class="text-gray-700 text-base flex items-start col-span-2">
                <x-icons.at class="h-5 inline mr-2"/>
                <a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>
            </p>
        @endif
        @if(($booking->booked_at->format("Y-m-d H:i:s") > \Carbon\Carbon::now()->setTimezone("Europe/London")->format("Y-m-d H:i:s") && $booking->booked_by == auth()->user()->id) || $restaurant->staff->contains(auth()->user()))
            <div class="col-span-2">
                <label><x-icons.user class="h-5 inline mr-2"/> Guests</label>
                <x-input class="w-full" type="number" min="1" wire:model.live="booking.covers"/>
                @error("booking.covers")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            @if($restaurant->staff->contains(auth()->user()))
                <div class="col-span-2">
                    <label><x-icons.table class="h-5 inline mr-2"/> Table</label>
                    <div class="border border-gray-200 rounded-sm w-full h-48 overflow-auto p-2 space-y-2 shadow-inner">
                        @foreach($restaurant->tables as $table)
                            <div>
                                <label class="block">
                                    <input type="checkbox" wire:model.live="bookingTables.{{ $table->getKey() }}" />
                                    {{ $table }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-gray-700 text-base flex items-start col-span-2">
                    <x-icons.table class="h-5 inline mr-2"/>
                    {{ $booking->tableNames }}
                </p>
            @endif
            <div>
                <label><x-icons.clock class="h-5 inline mr-2"/> Booked At</label>
                <x-input class="w-full" type="datetime-local" wire:model.live="booking.booked_at"/>
            </div>
            @if($restaurant->staff->contains(auth()->user()))
                <div>
                    <label><x-icons.clock class="h-5 inline mr-2"/> Finish At</label>
                    <x-input class="w-full" type="datetime-local" wire:model.live="booking.finish_at"/>
                </div>
            @else
                <div class="space-y-2">
                    <label><x-icons.clock class="h-5 inline mr-2"/> Finish At</label>
                    <p class="text-lg font-bold text-center">{{ $booking->finish_at->format("h:ia") }}</p>
                </div>
            @endif
        @else
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.user class="h-5 inline mr-2"/>
                {{ $booking->covers }} guests
            </p>
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.table class="h-5 inline mr-2"/>
                {{ $booking->tableNames }}
            </p>
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.calendar class="h-5 inline mr-2"/>
                {{ $booking->booked_at->ToFormattedDateString() }}
            </p>
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.clock class="h-5 inline mr-2"/>
                {{ $booking->booked_at->format("h:ia") }} - {{ $booking->finish_at->format("h:ia") }}
            </p>
        @endif
        @if($restaurant->staff->contains(auth()->user()))
            <div class="col-span-2">
                <label><x-icons.info class="h-5 inline mr-2"/> Additional Info</label>
                <x-input textarea class="w-full" wire:model.live="booking.comments"/>
            </div>
        @else
            @if(!empty($booking->comments))
                <p class="text-gray-700 text-base flex items-start col-span-2">
                    <x-icons.info class="h-5 inline mr-2"/>
                    <span class="whitespace-pre-line">{{ $booking->comments }}</span>
                </p>
            @endif
        @endif

        @if(($booking->booked_at->format("Y-m-d H:i:s") > \Carbon\Carbon::now()->setTimezone("Europe/London")->format("Y-m-d H:i:s") && $booking->booked_by == auth()->user()->id) || $restaurant->staff->contains(auth()->user()))
            <div class="col-span-2">
                <x-button type="submit" class="w-full justify-center bg-green-400 hover:bg-green-300">
                    <x-icons.save class="h-6 mr-2"/>
                    Update Booking
                </x-button>
                @error("booking")<span class="text-red-600">{{ $message }}</span>@enderror
                <x-action-message on="saved" class="text-center my-3">Saved</x-action-message>
            </div>
        @endif
    </form>

    @if(strtolower($booking->status) === "cancelled")
        <div class="bg-red-600 px-5 py-3 text-white flex items-center rounded w-full">
            <x-icons.info class="inline mr-2 h-5"/> Cancelled
        </div>
    @else
        <div class="space-y-5">
            @if($restaurant->staff->contains(auth()->user()))
                @livewire("restaurant.booking-status", compact(["booking"]))
            @endif

            @if($booking->booked_at->isFuture() && auth()->user()->id === $booking->booked_by)
                @livewire("booking.cancel", compact(["booking"]))
            @endif
        </div>
    @endif

</div>
