<x-jet-form-section submit="submit">
    <x-slot name="title">
        Profile
    </x-slot>
    <x-slot name="description">
        <p class="mb-3">Update basic restaurant details.</p>
        <p class="text-gray-500 text-sm">
            Please Note: We use your email address to verify your restaurant. If you change your email address, customers wont be able to see your restaurant until the new email address has been verified and approved by one of our team.
        </p>
    </x-slot>
    <x-slot name="form">
        <img class="col-span-6 md:col-span-3" src="{{ $image && empty($errors->first("image")) ? $image->temporaryUrl() : $restaurant->image }}">
        <div class="col-span-6 md:col-span-3 flex flex-wrap content-center">
            <input type="file" wire:model="image">
            @error("image")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Name</label>
            <x-jet-input wire:model="restaurant.name" class="w-full" />
            @error("restaurant.name")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Email</label>
            <x-jet-input wire:model="restaurant.email" class="w-full" />
            @error("restaurant.email")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Phone</label>
            <x-jet-input wire:model="restaurant.phone" class="w-full" />
            @error("restaurant.phone")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Description</label>
            <x-jet-input textarea wire:model="restaurant.description" class="w-full" rows="5" placeholder="A short description of your restaurant" />
            @error("restaurant.description")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
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
