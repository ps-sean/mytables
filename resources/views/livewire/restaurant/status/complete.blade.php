<div x-data="{ status: @entangle('status') }">
    <x-alert x-cloak x-show.transition.out="status === 'complete'" wire:click="goLive" class="bg-green-100 border-green-500 text-green-900 hover:bg-green-200 cursor-pointer transition-bg duration-150 ease-in-out">
        <p class="font-bold">Your restaurant has been verified. Click here to start accepting bookings.</p>
    </x-alert>
</div>

