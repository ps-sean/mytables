<div class="bg-gray-100 flex justify-between mx-5 px-5 py-3 rounded shadow items-center">
    @switch($booking->status)
        @case("confirmed")
        @case("seated")
        <p class="text-green-400 flex text-base"><x-icons.check class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
        @break
        @case("rejected")
        @case("cancelled")
        @case("no show")
        <p class="text-red-600 flex text-base"><x-icons.cross class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
        @break
        @default
        <p class="text-yellow-300 flex text-base"><x-icons.info class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</p>
        @break
    @endswitch
    <div class="space-x-2">
        @if($booking->status === "pending" || ($booking->status === "confirmed" && $booking->booked_at->format("Y-m-d H:i:s") > \Carbon\Carbon::now()->setTimezone("Europe/London")->format("Y-m-d H:i:s")))
            <x-button wire:loading.attr="disabled" wire:click.prevent="$set('reject_confirmation', true)" class="bg-red-600 hover:bg-red-500 font-bold"><x-icons.cross class="h-5 mr-1"/> <span class="hidden sm:flex">Reject</span></x-button>
        @endif

        @if(in_array($booking->status, ["pending", "rejected"]))
            <x-button wire:loading.attr="disabled" wire:click.prevent="$set('confirm_confirmation', true)" class="bg-green-400 hover:bg-green-300 font-bold"><x-icons.check class="h-5 mr-1"/> <span class="hidden sm:flex">Accept</span></x-button>
        @endif

        @if($booking->status === "confirmed")
            @if($booking->booked_at->format("Y-m-d H:i:s") < \Carbon\Carbon::now()->setTimezone("Europe/London")->format("Y-m-d H:i:s"))
                <x-button wire:loading.attr="disabled" wire:click.prevent="$set('no_show', true)" class="bg-red-600 hover:bg-red-500 font-bold"><x-icons.cross class="h-5 mr-1"/> <span class="hidden sm:flex">No Show</span></x-button>
            @endif
                <x-button wire:loading.attr="disabled" wire:click.prevent="$set('seat_table', true)" class="bg-green-400 hover:bg-green-300 font-bold"><x-icons.check class="h-5 mr-1"/> <span class="hidden sm:flex">Seat Table</span></x-button>
        @endif
    </div>

    <x-jet-confirmation-modal wire:model="reject_confirmation">
        <x-slot name="title">
            Reject Table
        </x-slot>

        <x-slot name="content">
            <p>Please confirm that you wish to reject this table. The customer will be notified of this change.</p>
            <label>Reason for rejecting (optional):</label>
            <x-jet-input textarea class="w-full" wire:model="booking.reject_reason" placeholder="Reason for rejecting"/>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:loading.attr="disabled" wire:click.prevent="$set('reject_confirmation', false)">
                Cancel
            </x-jet-secondary-button>
            <x-jet-danger-button wire:loading.attr="disabled" wire:click.prevent="bookingStatus('rejected')">
                Confirm
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <x-jet-dialog-modal wire:model="confirm_confirmation">
        <x-slot name="title">
            Accept Table
        </x-slot>

        <x-slot name="content">
            Please confirm that you wish to accept this table. The customer will be notified of this change.
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:loading.attr="disabled" wire:click.prevent="$set('confirm_confirmation', false)">
                Cancel
            </x-jet-secondary-button>
            <x-jet-button class="bg-green-400 hover:bg-green-300" wire:loading.attr="disabled" wire:click.prevent="bookingStatus('confirmed')">
                Confirm
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-confirmation-modal wire:model="no_show">
        <x-slot name="title">
            No Show
        </x-slot>

        <x-slot name="content">
            This should only be used when you are confident a booking will not show up. The status cannot be changed
            after this.
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:loading.attr="disabled" wire:click.prevent="$set('no_show', false)">
                Cancel
            </x-jet-secondary-button>
            @if($booking->payment_intent && $fee)
                <x-jet-danger-button wire:loading.attr="disabled" wire:click.prevent="$set('no_show_fee', true)">
                    Charge Fee
                </x-jet-danger-button>
            @endif
            <x-jet-button class="bg-green-400 hover:bg-green-300" wire:loading.attr="disabled" wire:click.prevent="noShow(false)">
                {{ $booking->payment_intent ? 'Dont Charge' : 'Confirm' }}
            </x-jet-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <x-jet-dialog-modal wire:model="no_show_fee">
        <x-slot name="title">
            Charge No Show Fee
        </x-slot>

        <x-slot name="content">
            This should only be used when you are confident a booking will not show up. Once the fee has been charged,
            it cannot be undone.

            {{ $booking->name }} will be charged <strong>&pound;{{ $fee }}</strong>.
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:loading.attr="disabled" wire:click.prevent="$set('no_show_fee', false)">
                Cancel
            </x-jet-secondary-button>
            <x-jet-button class="bg-green-400 hover:bg-green-300" wire:loading.attr="disabled" wire:click.prevent="noShow(true)">
                Confirm
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="seat_table">
        <x-slot name="title">
            Seat Table
        </x-slot>

        <x-slot name="content">
            Please confirm that the table have arrived and have been seated. This cannot be undone.
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:loading.attr="disabled" wire:click.prevent="$set('seat_table', false)">
                Cancel
            </x-jet-secondary-button>
            <x-jet-button class="bg-green-400 hover:bg-green-300" wire:loading.attr="disabled" wire:click.prevent="bookingStatus('seated')">
                Confirm
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
