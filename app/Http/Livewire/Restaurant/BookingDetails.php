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
    public $bookingTables = [];

    protected $rules = [
        "booking.covers" => "required|min:1",
        "booking.comments" => "",
        "booking.booked_at" => "required",
        "booking.finish_at" => "required",
        "bookingTables.*" => "",
    ];

    protected $listeners = ["update-booking" => "refresh"];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->restaurant = $booking->restaurant;

        foreach ($booking->table_ids as $tID) {
            $this->bookingTables[$tID] = true;
        }
    }

    public function render()
    {
        return view('livewire.restaurant.booking-details');
    }

    public function submit()
    {
        $this->resetErrorBag();
        $this->validate();

        if (count($this->bookingTables) < 1) {
            return $this->addError("bookingTables", "Please select at least 1 table");
        }

        $this->booking->table_ids = array_keys(array_filter($this->bookingTables));

        if (!$this->restaurant->staff->contains(Auth::user())) {
            // check there are services on that day
            if (!$this->booking->checkServices()->count()) {
                return $this->addError("booking", "There are no services available on " . $this->booking->booked_at->toDayDateTimeString());
            }

            // check the table size
            if ($this->booking->covers < $this->booking->getOriginal("covers")) {
                // check if there is a smaller table available
                if ($this->booking->tables->count() === 1) {
                    if ($table = $this->booking->assignTable($this->booking->tables->first()->restaurant_section_id)) {
                        if ($table->seats < $this->booking->tables->first()->seats) {
                            // assign to the smaller table
                            $this->booking->table_ids = [$table->id];
                        }
                    }
                }
            } else {
                if ($this->booking->covers > $this->booking->tables->first()->seats) {
                    if ($this->booking->tables->count() === 1) {
                        // check if there is another table
                        if ($table = $this->booking->assigntable($this->booking->tables->first()->restaurant_section_id)) {
                            $this->booking->table_ids = [$table->id];
                        } else {
                            return $this->addError("booking", $this->booking->tables->first() . " can only seat " . $this->booking->tables->first()->seats . " guests. There are no other tables available.");
                        }
                    } else {
                        return $this->addError("booking", "More than one table is being used for this booking, therefore you must ask the restaurant to add more guests.");
                    }
                }
            }
        }

        // check if this table is available
        if (!$this->booking->checkTime()) {
            if ($this->booking->tables->count() === 1) {
                // try to assign another table
                if ($table = $this->booking->assigntable($this->booking->tables->first()->section->id)) {
                    $this->booking->table_ids = [$table->id];
                } else {
                    // there are no tables available for this time
                    return $this->addError("booking", $this->booking->tables->first() . " is not available on " . $this->booking->booked_at->toDayDateTimeString() . ". We couldn't find any other available tables.");
                }
            } else {
                return $this->addError("booking", "One or more of your tables are not available at the time provided.");
            }
        }

        if (!$this->restaurant->staff->contains(Auth::user())) {
            // reset the status to pending
            $this->booking->status = "pending";

            // send a notification to the restaurant
            CustomerUpdatedBooking::dispatch($this->booking);
        }

        if ($this->booking->isDirty("booked_at")) {
            // booking time has changed
            if (!empty($this->booking->email)) {
                Mail::to($this->booking->email)->queue(new \App\Mail\Booking\StatusUpdate($this->booking));
            }

            if ($this->booking->booker) {
                Notification::send($this->booking->booker, new StatusUpdate($this->booking));
            }
        }

        $this->booking->save();

        $this->booking->refresh();

        $this->emitSelf("saved");
    }

    public function refresh()
    {
        $this->booking->refresh();
    }
}
