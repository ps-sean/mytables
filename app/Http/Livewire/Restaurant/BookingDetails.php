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
    public $booking, $restaurant;

    protected $rules = [
        "booking.covers" => "required|min:1",
        "booking.table_id" => "required",
        "booking.booked_at" => "required",
        "booking.finish_at" => "required",
    ];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->restaurant = $booking->restaurant;
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
            if(!empty($this->booking->email)){
                Mail::to($this->booking->email)->queue(new \App\Mail\Booking\StatusUpdate($this->booking));
            }

            if($this->booking->booker){
                Notification::send($this->booking->booker, new StatusUpdate($this->booking));
            }
        }

        $this->booking->save();

        $this->emitSelf("saved");
    }
}
