<div x-data="{ status: @entangle('status').live }">
    @if($restaurant->hasDefaultPaymentMethod())
        <x-alert x-cloak x-show.transition.out="status === 'offline'" wire:click="goLive" class="bg-red-200 border-red-600 text-red-600 hover:bg-red-100 cursor-pointer transition-bg duration-150 ease-in-out">
            <p class="font-bold">Your restaurant is currently offline. Click here to go live and accept bookings.</p>
        </x-alert>
    @endif

    <x-alert x-cloak x-show.transition.out="status === 'live'" wire:click="$toggle('offlineConfirmation')" class="bg-green-100 border-green-600 text-green-600 hover:bg-green-200 cursor-pointer transition-bg duration-150 ease-in-out">
        <p class="font-bold">Your restaurant is currently live. Click here if you need to go offline, this will prevent people being able to see your restaurant.</p>
    </x-alert>

    <x-confirmation-modal wire:model.live="offlineConfirmation">
        <x-slot name="title">Go Offline?</x-slot>
        <x-slot name="content">
            <p>Your restaurant will not show up in search results and will not be accessible by customers.</p>
            <p>If your restaurant is offline for more than 24 hours, you will not be charged and 1 day will be deducted from your next bill.</p>
        </x-slot>
        <x-slot name="footer">
            <x-button class="bg-red-600 hover:bg-red-500" wire:click="goOffline">Go Offline</x-button>
        </x-slot>
    </x-confirmation-modal>
</div>

