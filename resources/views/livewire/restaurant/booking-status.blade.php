<div class="bg-gray-100 flex justify-between mx-5 px-5 py-3 rounded shadow items-center">
    @switch($booking->status)
        @case("confirmed")
        <p class="text-green-400 flex text-base"><x-icons.check class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
        @break
        @case("rejected")
        @case("cancelled")
        <p class="text-red-600 flex text-base"><x-icons.cross class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
        @break
        @default
        <p class="text-yellow-300 flex text-base"><x-icons.info class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
        @break
    @endswitch
    <div class="space-x-2">
        @if($booking->status !== "rejected")
            <x-button wire:loading.attr="disabled" wire:click="bookingStatus('rejected')" class="bg-red-600 hover:bg-red-500 font-bold"><x-icons.cross class="h-5 mr-1"/> <span class="hidden sm:flex">Reject</span></x-button>
        @endif

        @if($booking->status !== "confirmed")
            <x-button wire:loading.attr="disabled" wire:click="bookingStatus('confirmed')" class="bg-green-400 hover:bg-green-300 font-bold"><x-icons.check class="h-5 mr-1"/> <span class="hidden sm:flex">Accept</span></x-button>
        @endif
    </div>
</div>
