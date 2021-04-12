<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Restaurant;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function show(Request $request, $restaurant)
    {
        $restaurant = Restaurant::findOrFail($restaurant);

        if(strtolower($restaurant->status->text) !== "live"){
            return view("restaurant.offline");
        }

        $specialHours = $restaurant->open_hours_exceptions()
            ->whereDate("open_date", "<=", \Carbon\Carbon::now()->addWeeks(2))
            ->orderBy("open_date")
            ->get();

        $defaultGroup = "all";

        if($restaurant->table_groups->count()){
            $defaultGroup = $restaurant->table_groups->first()->id;
        }

        $dates = CarbonPeriod::create(Carbon::now(), Carbon::now()->addDays(14))->toArray();
        $covers = $request->covers ?? 2;
        $group = $request->group ?? $defaultGroup;

        $selectedDate = $request->has("date") ? Carbon::parse($request->date) : Carbon::now();

        $services = $restaurant->loadServices($selectedDate, $covers, $group);

        return view("app.restaurant.show", compact([
            "restaurant",
            "dates",
            "services",
            "selectedDate",
            "specialHours",
            "covers",
            "group"
        ]));
    }

    public function book(Request $request, $restaurant)
    {
        $restaurant = Restaurant::findOrFail($restaurant);

        if(strtolower($restaurant->status->text) !== "live"){
            return view("restaurant.offline");
        }

        $booking = $restaurant->bookings()->make([
            "restaurant_id" => $restaurant->id,
            "covers" => $request->covers,
            "booked_at" => $request->time,
        ]);

        $group = $request->group;

        if(!empty($request->booking)){
            $booking->fill($request->booking);

            if($table = $booking->checkTime($group)){
                $booking->table_id = $table->id;
                $booking->save();
            }
        }

        return view("app.restaurant.book", compact([
            "restaurant",
            "booking",
            "group",
        ]));
    }
}
