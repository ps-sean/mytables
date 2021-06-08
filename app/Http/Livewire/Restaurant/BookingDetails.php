<?php

namespace App\Http\Livewire\Restaurant;

use App\Events\CustomerUpdatedBooking;
use App\Models\Booking;
use App\Notifications\Booking\StatusUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

        if(!$this->restaurant->staff->contains(Auth::user())){
            // check there are services on that day
            if(!$this->booking->checkServices()->count()){
                return $this->addError("booking", "There are no services available on " . $this->booking->booked_at->toDayDateTimeString());
            }

            // check the table size
            if($this->booking->covers < $this->booking->getOriginal("covers")){
                // check if there is a smaller table available
                if($table = $this->booking->assignTable($this->booking->tableNumber->table_group_id)){
                    if($table->seats < $this->booking->tableNumber->seats){
                        // assign to the smaller table
                        $this->booking->table_id = $table->id;
                    }
                }
            } else {
                if($this->booking->covers > $this->booking->tableNumber->seats){
                    // check if there is another table
                    if($table = $this->booking->assigntable($this->booking->tableNumber->table_group_id)){
                        $this->booking->table_id = $table->id;
                    } else {
                        return $this->addError("booking", $this->booking->tableNumber . " can only seat " . $this->booking->tableNumber->seats . " guests. There are no other tables available.");
                    }
                }
            }
        }

        // check if this table is available
        if(!$this->booking->checkTime()){
            // check if there is another table available
            if($table = $this->booking->assigntable($this->booking->tableNumber->table_group->id)){
                $this->booking->table_id = $table->id;

            } else {
                // there are no tables available for this time
                return $this->addError("booking", $this->booking->tableNumber . " is not available on " . $this->booking->booked_at->toDayDateTimeString() . ". There are no other tables available.");
            }
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

        if(!$this->restaurant->staff->contains(Auth::user())){
            // reset the status to pending
            $this->booking->status = "pending";
            $this->booking->save();

            // send a notification to the restaurant
            CustomerUpdatedBooking::dispatch($this->booking);
        }

        $this->booking->refresh();

        $this->emitSelf("saved");
    }
}
