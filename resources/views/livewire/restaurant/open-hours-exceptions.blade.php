<x-jet-dialog-modal wire:model="open">
    <x-slot name="title">
        Open Hours Exceptions
    </x-slot>
    <x-slot name="content">
        <div class="space-y-2 mb-4">
            @foreach($exceptions as $index => $exception)
                <div class="grid grid-cols-5 gap-2">
                    <div class="col-span-2">
                        <h5>Date</h5>
                        <p>{{ $exception->open_date->toFormattedDateString() }}</p>
                    </div>
                    <div>
                        <label>Open</label>
                        <x-jet-input class="w-full" type="time" wire:model="exceptions.{{ $index }}.open"/>
                        @error("exceptions.*.open")<span class="text-red-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label>Close</label>
                        <x-jet-input class="w-full" type="time" wire:model="exceptions.{{ $index }}.close"/>
                        @error("exceptions.*.close")<span class="text-red-600">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex items-center">
                        <x-button class="w-full justify-center bg-red-600 hover:bg-red-500" wire:click.prevent="removeException({{ $index }})">Delete</x-button>
                    </div>
                </div>
            @endforeach
        </div>

        <h5>Add New Exception</h5>
        <div class="grid grid-cols-5 gap-2">
            <div class="col-span-2">
                <label>Date</label>
                <x-jet-input class="w-full" type="date" wire:model="newException.open_date" :min="\Carbon\Carbon::now()->format('Y-m-d')"/>
                @error("newException.open_date")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            <div>
                <label>Open</label>
                <x-jet-input class="w-full" type="time" wire:model="newException.open"/>
                @error("newException.open")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            <div>
                <label>Close</label>
                <x-jet-input class="w-full" type="time" wire:model="newException.close"/>
                @error("newException.close")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            <div class="flex items-center">
                <x-button class="w-full justify-center bg-red-800 hover:bg-red-700" wire:click.prevent="addException">Add</x-button>
            </div>
        </div>
    </x-slot>
    <x-slot name="footer">
        <div class="flex justify-end items-center">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-jet-action-message>

            <x-jet-button wire:click="submit" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </div>
    </x-slot>
</x-jet-dialog-modal>
