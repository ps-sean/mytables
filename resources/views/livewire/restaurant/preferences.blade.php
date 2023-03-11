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
        @error("restaurant.table_confirmation")<p class="col-span-6 text-red-600">{{ $message }}</p>@enderror
        <p class="col-span-6">
            Show dates within the next <x-jet-input wire:model="restaurant.show_days" type="number" min="1" max="365"/> days.
        </p>
        <p class="col-span-6">
            Show customers tables every <x-jet-input wire:model="restaurant.interval" type="number" min="5" max="90"/> minutes.
        </p>
        @error("restaurant.table_confirmation")<p class="col-span-6 text-red-600">{{ $message }}</p>@enderror
        <p class="col-span-6">
            Accept a maximum of
            <x-jet-input wire:model="restaurant.booking_timeframe.covers" type="number" min="0"/>
            covers every
            <x-jet-input wire:model="restaurant.booking_timeframe.minutes" type="number" min="0" max="90"/>
            minutes.
        </p>
        @error("restaurant.booking_timeframe.covers")<p class="col-span-6 text-red-600">{{ $message }}</p>@enderror
        @error("restaurant.booking_timeframe.minutes")<p class="col-span-6 text-red-600">{{ $message }}</p>@enderror
        <p class="col-span-6">Leave a minimum of <x-jet-input wire:model="restaurant.turnaround_time" type="number" min="0" max="90"/> minutes between bookings. (Time to clean table before next booking)</p>
        @error("restaurant.turnaround_time")<p class="col-span-6 text-red-600">{{ $message }}</p>@enderror

        <p class="col-span-6">Pre-authorise an amount of &pound;<x-jet-input wire:model="restaurant.no_show_fee" type="number" min="0" max="100" step="5.00"/> per booking which can be charged in the event of a no-show.</p>
        <p class="col-span-6 text-sm text-gray-600 -mt-5">Please note: A 10% fee will be taken by myTables if a no-show fee is charged (no fees are taken for pre-authorisation). A pre-authorisation will be attempted 24 hours before a confirmed booking is due in and if unsuccessful, the booking status will automatically be set to "rejected".</p>
        @error("restaurant.no_show_fee")<p class="col-span-6 text-red-600">{{ $message }}</p>@enderror

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
