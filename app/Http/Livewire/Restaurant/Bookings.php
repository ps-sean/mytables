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

    public $restaurant, $date, $tables, $search, $newBooking, $nextBooking, $services;
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
        "newBooking.table_id" => "required",
        "newBooking.name" => "required",
        "newBooking.email" => "email",
        "newBooking.contact_number" => "min:11|max:16|phone",
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
            $bookings = $this->restaurant->bookings()->whereDate("booked_at", $this->date)->whereNotIn("status", ["cancelled", "rejected", "no show"])->orderBy("booked_at")->get();
        } else {
            $bookings = $this->restaurant->bookings()->whereDate("booked_at", ">=", Carbon::now())->orderBy("booked_at");

            if($this->status !== "all"){
                $bookings = $bookings->where("status", $this->status);
            }

            if(!empty($this->search)){
                $bookings = $bookings->where("name", 'like', '%' . $this->search . '%');
            }

            $bookings = $bookings->paginate(25);
        }

        return view('livewire.restaurant.bookings', compact([
            "period",
            "bookings"
        ]));
    }

    public function setTables()
    {
        $this->bookings = $this->restaurant->bookings()->whereDate("booked_at", $this->date)->whereNotIn("status", ["cancelled", "rejected", "no show"])->orderBy("booked_at");

        $this->tables = $this->restaurant->tables->sortBy("table_group_id")->sortBy("name", SORT_NATURAL);

        foreach($this->bookings->get() as $booking){
            if(!$this->tables->contains($booking->tableNumber)){
                $this->tables->push($booking->tableNumber);
            }
        }
    }

    public function createNewBooking($time = null, $table = null)
    {
        $this->resetErrorBag();

        $this->newBooking = $this->restaurant->bookings()->make();

        $this->newBooking->covers = 2;
        $this->newBooking->booked_at = $time;
        $this->newBooking->table_id = $table;

        $this->createBooking = true;
    }

    public function submitBooking()
    {
        $this->validate();

        $this->newBooking->load('tableNumber');
        $time = $this->newBooking->booked_at;
        $this->nextBooking = $this->newBooking->tableNumber->bookings()->whereNotIn("status", ["rejected", "cancelled", "no show"])->where("booked_at", ">", $time)->first();

        if($this->nextBooking){
            $this->validate([
                "newBooking.finish_at" => "required|before_or_equal:" . $this->nextBooking->booked_at->format("Y-m-d H:i")
            ]);
        }

	    $this->newBooking->status = "confirmed";

        $this->newBooking->save();

        $this->createBooking = false;
    }
}
