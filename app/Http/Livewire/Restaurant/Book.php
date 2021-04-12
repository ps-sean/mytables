<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Booking;
use App\Models\Restaurant;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Book extends Component
{
    public $restaurant, $dates, $selectedDate, $times, $services, $booking;
    public $group = "all";
    public $covers = 2;

    protected $rules = [
        "booking.covers" => ["required", "min:1"],
        "booking.name" => ["required"],
        "booking.restaurant_id" => ["required"],
        "booking.booked_at" => ["required"],
        "booking.booked_by" => [],
        "booking.email" => ["required", "email"],
        "booking.contact_number" => ['required', 'min:11', 'max:16', 'phone'],
        "booking.comments" => [],
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->selectedDate = Carbon::now();

        $this->rules["booking.covers"][] = "max:" . $restaurant->max_booking_size;

        if($restaurant->table_groups->count()){
            $this->group = $restaurant->table_groups->first()->id;
        }
    }

    public function render()
    {
        $this->dates = CarbonPeriod::create(Carbon::now(), Carbon::now()->addDays(14))->toArray();
        $this->services = $this->restaurant->loadServices($this->selectedDate, $this->covers, $this->group);

        return view('livewire.restaurant.book');
    }

    public function selectDate($newDate)
    {
        $this->selectedDate = Carbon::parse($newDate);
    }

    public function showBooking($time)
    {
        $this->booking = new Booking();
        $this->booking->restaurant_id = $this->restaurant->id;
        $this->booking->covers = $this->covers;
        $this->booking->booked_at = Carbon::parse($time);

        if(Auth::check()){
            $this->booking->booked_by = Auth::user()->id;
            $this->booking->name = Auth::user()->name;
            $this->booking->email = Auth::user()->email;
            $this->booking->contact_number = Auth::user()->contact_number;
        }

        // before passing back, check booking is valid
        if(!$this->booking->checkTime($this->group)){
            session()->flash("timeTaken");
        }
    }

    public function hideBooking()
    {
        $this->booking = null;
    }

    public function adjustCovers($adjustment)
    {
        if(1 <= ($this->covers + $adjustment)){
            $this->covers += $adjustment;
        }
    }

    public function book()
    {
        $this->validate();

        // check one last time for a table
        if($table = $this->booking->checkTime($this->group)){
            $this->booking->table_id = $table->id;

            $this->booking->save();

            if(Auth::check()){
                $user = Auth::user();

                if(empty($user->contact_number)){
                    $user->contact_number = $this->booking->contact_number;
                    $user->save();
                }
            }
        } else {
            session()->flash('timeTaken', 'This time has been taken');
        }
    }
}
