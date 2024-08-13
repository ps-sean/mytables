<div>
    <x-button wire:click="$set('confirmCancel', true)" class="bg-red-600 hover:bg-red-500 justify-center w-full"><x-icons.cross class="h-5 inline mr-2"/> Cancel Booking</x-button>

    <x-confirmation-modal wire:model.live="confirmCancel">
        <x-slot name="title">
            Cancel Booking
        </x-slot>
        <x-slot name="content">
            Are you sure you want to cancel this booking?
        </x-slot>
        <x-slot name="footer">
            <x-button wire:loading.attr="disabled" wire:click="cancel" class="bg-red-600 hover:bg-red-500 justify-center">Cancel Booking</x-button>
        </x-slot>
    </x-confirmation-modal>
</div>
