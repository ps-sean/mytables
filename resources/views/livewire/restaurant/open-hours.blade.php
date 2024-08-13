<x-form-section submit="submit">
    <x-slot name="title">
        Opening Hours
    </x-slot>
    <x-slot name="description">
        <p class="mb-3">Update your restaurants opening hours.</p>
        <x-button class="bg-red-800 hover:red-700 mt-3" wire:click="$dispatch('openOpenHoursExceptions')">Exceptions @if($count = $restaurant->open_hours_exceptions->count())<span class="bg-white rounded-full ml-3 text-black px-2">{{ $count }}</span>@endif</x-button>
        @livewire("restaurant.open-hours-exceptions", compact(["restaurant"]))
    </x-slot>
    <x-slot name="form">
        <div class="font-bold">
            <p>Day</p>
        </div>
        <div class="col-span-2 font-bold">
            <p>Open</p>
        </div>
        <div class="col-span-2 font-bold">
            <p>Close</p>
        </div>
        <div></div>
        @foreach(\App\Models\Service::DAYS_OF_WEEK as $day)
            <div>
                {{ $day }}
            </div>
            <div class="col-span-2">
                <x-input class="w-full" type="time" wire:model.live="restaurant.open_hours.{{ $day }}.open"/>
            </div>
            <div class="col-span-2">
                <x-input class="w-full" type="time" wire:model.live="restaurant.open_hours.{{ $day }}.close"/>
            </div>
            <div></div>
        @endforeach
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
