<div class="p-5">
    <form class="p-5 md:grid md:grid-cols-2 gap-4 space-y-4" wire:submit.prevent="submit">
        <p class="text-gray-700 text-2xl font-bold col-span-2">
            {{ $booking->name }}
        </p>
        <p class="text-gray-700 text-base flex items-start col-span-2">
            <x-icons.phone class="h-5 inline mr-2"/>
            <a href="tel:{{ $booking->contact_number }}">{{ $booking->contact_number }}</a>
        </p>
        @if(!empty($booking->email))
            <p class="text-gray-700 text-base flex items-start col-span-2">
                <x-icons.at class="h-5 inline mr-2"/>
                <a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>
            </p>
        @endif
        @if($booking->booked_at->isFuture() && $restaurant->staff->contains(auth()->user()))
            <div>
                <label><x-icons.user class="h-5 inline mr-2"/> Guests</label>
                <x-jet-input class="w-full" type="number" min="1" wire:model="booking.covers"/>
                @error("booking.covers")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            <div>
                <label><x-icons.table class="h-5 inline mr-2"/> Table</label>
                <x-select class="w-full" wire:model="booking.table_id">
                    @foreach($restaurant->tables()->orderBy("table_group_id")->orderBy("name")->get() as $table)
                        <option value="{{ $table->id }}">{{ $table }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <label><x-icons.clock class="h-5 inline mr-2"/> Booked At</label>
                <x-jet-input class="w-full" type="datetime-local" wire:model="booking.booked_at"/>
            </div>
            <div>
                <label><x-icons.clock class="h-5 inline mr-2"/> Finish At</label>
                <x-jet-input class="w-full" type="datetime-local" wire:model="booking.finish_at"/>
            </div>
        @else
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.user class="h-5 inline mr-2"/>
                {{ $booking->covers }} guests
            </p>
            <p class="text-gray-700 text-base flex items-start">
                <x-icons.table class="h-5 inline mr-2"/>
                {{ $booking->tableNumber }}
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
        @if(!empty($booking->comments))
            <p class="text-gray-700 text-base flex items-start col-span-2">
                <x-icons.info class="h-5 inline mr-2"/>
                <span class="whitespace-pre-line">{{ $booking->comments }}</span>
            </p>
        @endif

        @if($booking->booked_at->isFuture() && $restaurant->staff->contains(auth()->user()))
            <div class="col-span-2">
                <x-button type="submit" class="w-full justify-center bg-green-400 hover:bg-green-300">
                    <x-icons.save class="h-6 mr-2"/>
                    Save Details
                </x-button>
                @error("booking")<span class="text-red-600">{{ $message }}</span>@enderror
                <x-jet-action-message on="saved" class="text-center my-3">Saved</x-jet-action-message>
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
