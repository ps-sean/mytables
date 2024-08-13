<x-form-section submit="submit">
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
        <div class="col-span-6 grid grid-cols-1 md:grid-cols-2">
            <label class="md:col-span-2">Restaurant Logo</label>
            <img src="{{ $logo && empty($errors->first("logo")) ? $logo->temporaryUrl() : $restaurant->logo }}">
            <div class="flex flex-wrap content-center">
                <input type="file" wire:model.live="logo">
                @error("logo")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-span-6 grid grid-cols-1 md:grid-cols-2">
            <label class="md:col-span-2">Restaurant Image</label>
            <img src="{{ $image && empty($errors->first("image")) ? $image->temporaryUrl() : $restaurant->image }}">
            <div class="flex flex-wrap content-center">
                <input type="file" wire:model.live="image">
                @error("image")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Name</label>
            <x-input wire:model.live="restaurant.name" class="w-full" />
            @error("restaurant.name")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Email</label>
            <x-input wire:model.live="restaurant.email" class="w-full" />
            @error("restaurant.email")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Phone</label>
            <x-input wire:model.live="restaurant.phone" class="w-full" />
            @error("restaurant.phone")<span class="text-red-600">{{ $message }}</span>@enderror
        </div>
        <div class="col-span-6 sm:col-span-4">
            <label>Description</label>
            <x-input textarea wire:model.live="restaurant.description" class="w-full" rows="5" placeholder="A short description of your restaurant" />
            @error("restaurant.description")<span class="text-red-600">{{ $message }}</span>@enderror
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
