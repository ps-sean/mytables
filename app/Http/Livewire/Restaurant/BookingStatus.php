<?php

namespace App\Http\Livewire\Restaurant;

use App\Events\BookingStatusUpdated;
use App\Models\Booking;
use Livewire\Component;

class BookingStatus extends Component
{
    public $booking;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function render()
    {
        return view('livewire.restaurant.booking-status');
    }

    public function bookingStatus($status)
    {
        $this->booking->status = $status;
        $this->booking->save();

        BookingStatusUpdated::dispatch($this->booking);
    }
}
