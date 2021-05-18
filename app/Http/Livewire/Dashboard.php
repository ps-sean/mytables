<?php

namespace App\Http\Livewire;

use App\Models\Restaurant;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $restaurant, $restaurants, $bookings, $covers, $reviews, $rid;

    protected $rules = [
        "restaurant" => ""
    ];

    protected $queryString = [
        "rid"
    ];

    public function mount()
    {
        $this->restaurants = Auth::user()->restaurants;
        $this->restaurant = $this->restaurants->first();
        $this->rid = $this->restaurant->id;

        $this->bookingStats();
        $this->reviewStats();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function updatedRid($value)
    {
        $restaurant = Restaurant::find($value);

        if(Auth::user()->restaurants->contains($restaurant)){
            $this->restaurant = $restaurant;

            $this->bookingStats();
            $this->reviewStats();
            $this->dispatchBrowserEvent("updated-restaurant");
        }
    }

    public function bookingStats()
    {
        $period = CarbonPeriod::create(Carbon::now()->subDays(6), CarbonInterval::day(), Carbon::now());

        $this->bookings = [
            ["Day", "This Week", "Last Week", "Next Week"]
        ];

        $this->covers = [
            ["Day", "This Week", "Last Week", "Next Week"]
        ];

        foreach($period as $day){
            $bookings = $this->restaurant->bookings()
                ->whereNotIn("status", ["cancelled", "rejected"])
                ->whereDate("booked_at", $day)
                ->get();

            $lastWeekBookings = $this->restaurant->bookings()
                ->whereNotIn("status", ["cancelled", "rejected"])
                ->whereDate("booked_at", $day->copy()->subWeek())
                ->get();

            $nextWeekBookings = $this->restaurant->bookings()
                ->whereNotIn("status", ["cancelled", "rejected"])
                ->whereDate("booked_at", $day->copy()->addWeek())
                ->get();

            $d = $day->shortEnglishDayOfWeek;

            $this->bookings[] = [$d, $bookings->count(), $lastWeekBookings->count(), $nextWeekBookings->count()];
            $this->covers[] = [$d, $bookings->sum("covers"), $lastWeekBookings->sum("covers"), $nextWeekBookings->sum("covers")];
        }
    }

    public function reviewStats()
    {
        $period = CarbonPeriod::create(Carbon::now()->startOfMonth()->subMonths(11), CarbonInterval::month(), Carbon::now()->startOfMonth());

        $this->reviews = [
            ["Month", "Count", "Avg. Price", "Avg. Product", "Avg. Service", "Avg. Cleanliness", "Avg. Overall"]
        ];

        foreach($period as $month){
            // get reviews for this month
            $reviews = $this->restaurant->reviews()
                ->whereDate("reviews.created_at", ">=", $month->copy()->startOfMonth())
                ->whereDate("reviews.created_at", "<=", $month->copy()->endOfMonth())
                ->get();

            $this->reviews[] = [
                $month->format("M Y"),
                $reviews->count(),
                $reviews->avg("price") ?? 0,
                $reviews->avg("product") ?? 0,
                $reviews->avg("service") ?? 0,
                $reviews->avg("cleanliness") ?? 0,
                $reviews->avg("overall") ?? 0,
            ];
        }
    }
}
