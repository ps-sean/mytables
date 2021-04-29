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
    public $restaurant, $restaurants, $bookings, $covers, $reviews;

    protected $queryString = ["restaurant"];

    public function mount()
    {
        $this->restaurants = Auth::user()->restaurants;
        $this->restaurant = $this->restaurants->first()->id;

        $this->bookingStats();
        $this->reviewStats();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function bookingStats()
    {
        $restaurant = Restaurant::find($this->restaurant);
        $period = CarbonPeriod::create(Carbon::now()->subDays(6), CarbonInterval::day(), Carbon::now());

        $this->bookings = [
            ["Day", "This Week", "Last Week"]
        ];

        $this->covers = [
            ["Day", "This Week", "Last Week"]
        ];

        foreach($period as $day){
            $bookings = $restaurant->bookings()
                ->whereNotIn("status", ["cancelled", "rejected"])
                ->whereDate("booked_at", $day)
                ->get();

            $lastWeekBookings = $restaurant->bookings()
                ->whereNotIn("status", ["cancelled", "rejected"])
                ->whereDate("booked_at", $day->copy()->subWeek())
                ->get();

            $d = $day->shortEnglishDayOfWeek;

            if($day->isToday()){
                $d = "Today";
            }

            $this->bookings[] = [$d, $bookings->count(), $lastWeekBookings->count()];
            $this->covers[] = [$d, $bookings->sum("covers"), $lastWeekBookings->count("covers")];
        }
    }

    public function reviewStats()
    {
        $restaurant = Restaurant::find($this->restaurant);
        $period = CarbonPeriod::create(Carbon::now()->startOfMonth()->subMonths(11), CarbonInterval::month(), Carbon::now()->startOfMonth());

        $this->reviews = [
            ["Month", "Count", "Avg. Price", "Avg. Product", "Avg. Service", "Avg. Cleanliness", "Avg. Overall"]
        ];

        foreach($period as $month){
            // get reviews for this month
            $reviews = $restaurant->reviews()
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
