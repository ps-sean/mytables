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
            <x-select wire:model="bookingConfirmation">
                <option value="automatic">automatically</option>
                <option value="manual">manually</option>
            </x-select>
            confirmed when booking.
        </p>
        <p class="col-span-6">
            The maximum number of bookings within a timeframe is
            <x-jet-input wire:model="bookingTimeframe.tables" type="number" min="0" max="{{ $restaurant->tables->count() }}"/>
            tables every
            <x-jet-input wire:model="bookingTimeframe.minutes" type="number" min="0" max="90"/>
            minutes.
        </p>
        <p class="col-span-6">Leave a minimum of <x-jet-input wire:model="bookingTurnaround" type="number" min="0" max="90" step="{{ $bookingTimeframe['minutes'] }}"/> minutes between bookings. (Time to clean table before next booking)</p>
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
