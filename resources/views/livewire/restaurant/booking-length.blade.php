<div x-data="{ open: @entangle('open').live }">
    <x-jet-form-section submit="submit">
        <x-slot name="title">
            Booking Rules
        </x-slot>

        <x-slot name="description">
            Decide how long bookings can stay at a table.
        </x-slot>

        <x-slot name="form">
            <div x-show.transition="open" class="col-span-6 space-y-2">
                    @foreach($booking_rules as $index => $rule)
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label>Max Guests</label>
                                <x-jet-input class="w-full" type="number" wire:model.live="booking_rules.{{ $index }}.max_covers" min="1" max="{{ $restaurant->max_booking_size() }}" required />
                                @error("booking_rules.$index.max_covers")<span class="text-red-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label>Minutes</label>
                                <x-jet-input class="w-full" type="number" wire:model.live="booking_rules.{{ $index }}.minutes" min="5" step="5" required />
                                @error("booking_rules.$index.minutes")<span class="text-red-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <x-button class="bg-red-600 hover:bg-red-500 w-full justify-center" wire:click="removeRule({{ $index }})">
                                    <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Delete
                                </x-button>
                            </div>
                        </div>
                    @endforeach
                <div class="flex justify-center">
                    <x-button wire:click="addRule" class="bg-red-800 hover:bg-red-700">Add Rule</x-button>
                </div>
            </div>

            <div x-show.transition="!open" class="col-span-6 text-right">
                <x-button @click="open = true" class="bg-red-800 hover:bg-red-700">
                    <svg class="h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Show
                </x-button>
            </div>
        </x-slot>

        @if($open)
            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-button @click="open = false" class="bg-red-800 hover:bg-red-700 mr-3">
                    <svg class="h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    Hide
                </x-button>

                <x-jet-button wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-jet-button>
            </x-slot>
        @endif
    </x-jet-form-section>
</div>
