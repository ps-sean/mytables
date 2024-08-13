<x-form-section submit="submit">
    <x-slot name="title">
        Staff
    </x-slot>
    <x-slot name="description">
        Add/Remove staff Accounts from this restaurant.
    </x-slot>
    <x-slot name="form">
        <div class="col-span-6 flex gap-2">
            @foreach($staff as $index => $s)
                <div class="bg-red-200 text-red-800 border border-red-800 rounded px-2 py-1 flex justify-between space-x-2" title="{{ $s->email }}">
                    <p>{{ $s }}</p>
                    @if($s->id !== auth()->user()->id)
                        <a class="transition-all duration-150 ease-in-out hover:text-red-500" href="#remove-staff" wire:click="removeStaff({{ $index }})">&times;</a>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="col-span-5">
            <x-input class="w-full" placeholder="Add Staff By Email Address" wire:model.live="search" wire:keydown.enter.prevent="addStaff"/>
        </div>
        <x-button class="bg-red-800 hover:bg-red-700 justify-center" wire:click="addStaff"><x-icons.plus class="h-6 mr-1"/> Add</x-button>
        <div class="col-span-6">
            @error("search")<span class="text-red-600">{{ $message }}</span>@enderror
            <x-action-message on="staffAdded">Staff member added, click save to make changes permanent.</x-action-message>
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
