<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Booking;
use App\Notifications\Booking\StatusUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class BookingDetails extends Component
{
    public $booking, $restaurant, $bookedAt;

    protected $rules = [
        "booking.covers" => "required|min:1",
        "booking.table_id" => "required",
        "booking.booked_at" => "required"
    ];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->restaurant = $booking->restaurant;
        $this->bookedAt = $this->booking->booked_at->format("Y-m-d\TH:i:s");
    }

    public function updatedBookedAt($value)
    {
        $this->booking->booked_at = $value;
    }

    public function render()
    {
        return view('livewire.restaurant.booking-details');
    }

    public function submit()
    {
        $this->resetErrorBag();
        $this->validate();

        if(!$this->booking->checkTime()){
            $this->addError("booking", $this->booking->tableNumber . " is not available on " . $this->booking->booked_at->toDayDateTimeString());
            return;
        }

        if($this->booking->isDirty("booked_at")){
            // booking time has changed
            Mail::to($this->booking->email)->send(new \App\Mail\Booking\StatusUpdate($this->booking));
            Notification::send($this->booking->booker, new StatusUpdate($this->booking));
        }

        $this->booking->save();

        $this->emitSelf("saved");
    }
}
