<div>
    <x-form-section submit="submit">
        <x-slot name="title">Payment Date</x-slot>
        <x-slot name="description">
            Change your payment date. Any changes will be prorated.
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6">
                <p>
                    Charge my account on the
                    <x-select wire:model.live="restaurant.billing_date">
                        <option value="1">1st</option>
                        <option value="2">2nd</option>
                        <option value="3">3rd</option>
                        @for($i = 4; $i <= 28; $i++)
                            <option value="{{ $i }}">{{ $i }}th</option>
                        @endfor
                    </x-select>
                    of every month.
                </p>
                @error("restaurant.billing_date")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button class="bg-red-800 hover:bg-red-700 text-white" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
