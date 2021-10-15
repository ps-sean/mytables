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
use Livewire\WithPagination;

class Bookings extends Component
{
    use WithPagination;

    public $restaurant, $date, $tables, $search, $newBooking, $services, $bookings;
    public $newBookingTables = [];
    public $status = "all";
    public $createBooking = false;
    public $view = "grid";
    public $show = ["name", "guests", "table", "time", "comments"];
    public $size = "medium";
    public $sizes = [
        "small" => [
            "text" => "text-sm",
            "col" => "25",
        ],
        "medium" => [
            "text" => "text-md",
            "col" => "50",
        ],
        "large" => [
            "text" => "text-lg",
            "col" => "75",
        ],
    ];

    protected $queryString = ['search', 'date', 'view', 'status', 'show', 'size'];

    protected $rules = [
        "newBooking.restaurant_id" => "required",
        "newBooking.covers" => "required|min:1",
        "newBooking.booked_at" => "required",
        "newBooking.finish_at" => "required",
        "newBooking.name" => "required",
        "newBooking.email" => "email",
        "newBooking.contact_number" => "min:11|max:16|phone",
        "newBooking.comments" => "",
        "newBookingTables.*" => "",
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

    public function updatedSearch()
    {
        $this->dispatchBrowserEvent("search");
    }

    public function updatedNewBookingCovers()
    {
        // hack to update the finish time when covers is changed
        $this->newBooking->setBookedAtAttribute($this->newBooking->booked_at);
    }

    public function render()
    {
        $this->services = $this->restaurant->servicesByDate(Carbon::parse($this->date));
        $period = $this->restaurant->servicePeriod(Carbon::parse($this->date));

        if($this->view === "grid"){
            $reservations = $this->bookings;
        } else {
            $reservations = $this->restaurant->bookings()->whereDate("booked_at", ">=", Carbon::now())->orderBy("booked_at");

            if($this->status !== "all"){
                $reservations = $reservations->where("status", $this->status);
            }

            if(!empty($this->search)){
                $reservations = $reservations->where("name", 'like', '%' . $this->search . '%');
            }

            $reservations = $reservations->paginate(25);
        }

        return view('livewire.restaurant.bookings', compact([
            "period",
            "reservations"
        ]));
    }

    public function setTables()
    {
        $this->bookings = $this->restaurant->bookings()->whereDate("booked_at", $this->date)->whereNotIn("status", ["cancelled", "rejected", "no show"])->orderBy("booked_at")->get();

        $this->tables = $this->restaurant->tables->sortBy("restaurant_section_id")->sortBy("name", SORT_NATURAL);

        foreach($this->bookings as $booking){
            $this->tables = $this->tables->merge($booking->tables);
        }
    }

    public function createNewBooking($time = null, $table = null)
    {
        $this->resetErrorBag();

        $this->newBooking = $this->restaurant->bookings()->make();

        $this->newBooking->covers = 2;
        $this->newBooking->booked_at = $time;

        if ($table) {
            $this->newBookingTables = [
                (int)$table => true,
            ];
        }

        $this->createBooking = true;
    }

    public function submitBooking()
    {
        $this->validate();

        if (count($this->newBookingTables) < 1) {
            return $this->addError("newBookingTables", "PLease select at least 1 table");
        }

        $this->newBooking->table_ids = array_keys(array_filter($this->newBookingTables));

        foreach ($this->newBooking->tables as $table) {
            $nextBooking = $table->bookings()
                ->whereNotIn("status", ["rejected", "cancelled", "no show"])
                ->where("booked_at", ">", $this->newBooking->booked_at)
                ->first();

            if ($nextBooking && $nextBooking->booked_at < $this->newBooking->finish_at) {
                return $this->addError("newBooking.finish_at", $table . " is being used by " . $nextBooking);
            }
        }

	    $this->newBooking->status = "confirmed";

        $this->newBooking->save();

        $this->createBooking = false;
    }

    public function fetchBooking($bookings, $table, $time)
    {
        return $bookings->filter(function ($booking, $key) use ($table) {
            return $booking->tables->contains($table);
        })->whereBetween("booked_at", [$time, $time->copy()->addMinutes($this->restaurant->interval - 1)])
            ->first();
    }
}
