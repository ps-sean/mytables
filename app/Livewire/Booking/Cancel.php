<?php

namespace App\Livewire\Booking;

use App\Events\BookingStatusUpdated;
use App\Models\Booking;
use App\Notifications\Booking\StatusUpdate;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Cancel extends Component
{
    public $booking;
    public $confirmCancel = false;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function render()
    {
        return view('livewire.booking.cancel');
    }

    public function cancel()
    {
        $this->booking->status = "cancelled";
        $this->booking->save();

        BookingStatusUpdated::dispatch($this->booking);

        return redirect(route("bookings"));
    }
}
