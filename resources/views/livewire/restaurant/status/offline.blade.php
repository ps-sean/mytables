<div x-data="{ status: @entangle('status') }">
    <x-alert x-cloak x-show.transition.out="status === 'offline'" wire:click="goLive" class="bg-red-200 border-red-600 text-red-600 hover:bg-red-100 cursor-pointer transition-bg duration-150 ease-in-out">
        <p class="font-bold">Your restaurant is currently offline. Click here to go live and accept bookings.</p>
    </x-alert>
</div>

