<x-jet-form-section submit="submit">
    <x-slot name="title">
        Preferences
    </x-slot>

    <x-slot name="description">
        Check the Following Statements and Update your Preferences.
    </x-slot>

    <x-slot name="form">
        <p class="col-span-6">
            Tables are
            <x-select wire:model="restaurant.table_confirmation">
                <option value="automatic">automatically</option>
                <option value="manual">manually</option>
            </x-select>
            confirmed when booking.
        </p>
        @error("restaurant.table_confirmation")<p class="text-red-600">{{ $message }}</p>@enderror
        <p class="col-span-6">
            Show customers tables every <x-jet-input wire:model="restaurant.interval" type="number" min="5" max="90"/> minutes.
        </p>
        @error("restaurant.table_confirmation")<p class="text-red-600">{{ $message }}</p>@enderror
        <p class="col-span-6">
            The maximum number of bookings within a timeframe is
            <x-jet-input wire:model="restaurant.booking_timeframe.tables" type="number" min="0" max="{{ $restaurant->tables->count() }}"/>
            tables every
            <x-jet-input wire:model="restaurant.booking_timeframe.minutes" type="number" min="0" max="90"/>
            minutes.
        </p>
        @error("restaurant.booking_timeframe.tables")<p class="text-red-600">{{ $message }}</p>@enderror
        @error("restaurant.booking_timeframe.minutes")<p class="text-red-600">{{ $message }}</p>@enderror
        <p class="col-span-6">Leave a minimum of <x-jet-input wire:model="restaurant.turnaround_time" type="number" min="0" max="90"/> minutes between bookings. (Time to clean table before next booking)</p>
        @error("restaurant.turnaround_time")<p class="text-red-600">{{ $message }}</p>@enderror

        @if($errors->any())
            <x-alert class="col-span-6 border-red-600 text-red-600 bg-red-200">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
