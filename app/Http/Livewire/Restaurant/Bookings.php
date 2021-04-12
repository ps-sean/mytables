<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\Team;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Bookings extends Component
{
    public $bookings, $restaurant, $date, $tables, $search, $newBooking, $nextBooking, $services;
    public $createBooking = false;

    protected $queryString = ['search', 'date'];

    protected $rules = [
        "newBooking.restaurant_id" => "required",
        "newBooking.covers" => "required|min:1",
        "newBooking.booked_at" => "required",
        "newBooking.finish_at" => "required",
        "newBooking.table_id" => "required",
        "newBooking.name" => "required",
        "newBooking.email" => "email",
        "newBooking.contact_number" => "required|min:11|max:16|phone",
        "newBooking.comments" => "",
    ];

    public function mount(Restaurant $restaurant, $date)
    {
        $this->restaurant = $restaurant;
        $this->date = $date->format("Y-m-d");

        $this->setTables();
    }

    public function getListeners()
    {
        $userID = Auth::user()->id;

        $listenOn = [];

        if(Auth::user()->restaurants->count()){
            $listenOn["echo-notification:App.Models.User.{$userID},Restaurant\\Booking"] = "setTables";
        }

        return $listenOn;
    }

    public function render()
    {
        $this->services = $this->restaurant->servicesByDate(Carbon::parse($this->date));
        $period = $this->restaurant->servicePeriod(Carbon::parse($this->date));

        return view('livewire.restaurant.bookings', compact([
            "period"
        ]));
    }

    public function setTables()
    {
        $this->bookings = $this->restaurant->bookings()->whereDate("booked_at", $this->date)->whereNotIn("status", ["cancelled", "rejected"])->orderBy("booked_at")->get();

        $this->tables = $this->restaurant->tables()->orderBy("table_group_id")->orderBy("name")->get();

        foreach($this->bookings as $booking){
            if(!$this->tables->contains($booking->tableNumber)){
                $this->tables->push($booking->tableNumber);
            }
        }
    }

    public function createNewBooking($time, $table)
    {
        $this->resetErrorBag();

        $this->newBooking = $this->restaurant->bookings()->make();

        $this->newBooking->covers = 2;
        $this->newBooking->booked_at = $time;
        $this->newBooking->table_id = $table;

        $this->nextBooking = $this->newBooking->tableNumber->bookings()->whereNotIn("status", ["rejected", "cancelled"])->where("booked_at", ">", $time)->first();

        $this->createBooking = true;
    }

    public function submitBooking()
    {
        if($this->nextBooking){
            $this->validate([
                "newBooking.finish_at" => "required|before_or_equal:" . $this->nextBooking->booked_at->format("Y-m-d H:i")
            ]);
        }

        $this->validate();

        $this->newBooking->status = "confirmed";

        $this->newBooking->save();

        $this->createBooking = false;
    }
}
