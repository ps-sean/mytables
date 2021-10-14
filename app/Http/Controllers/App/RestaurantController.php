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

        $defaultSection = "all";

        if($restaurant->sections->count()){
            $defaultSection = $restaurant->sections->first()->id;
        }

        $dates = CarbonPeriod::create(Carbon::now(), Carbon::now()->addDays($restaurant->show_days))->toArray();
        $covers = $request->covers ?? 2;
        $section = $request->section ?? $defaultSection;

        $selectedDate = $request->has("date") ? Carbon::parse($request->date) : Carbon::now();

        $services = $restaurant->loadServices($selectedDate, $covers, $section);

        return view("app.restaurant.show", compact([
            "restaurant",
            "dates",
            "services",
            "selectedDate",
            "specialHours",
            "covers",
            "section"
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

        $section = $request->section;

        if(!empty($request->booking)){
            $booking->fill($request->booking);

            if($table = $booking->checkTime($section)){
                $booking->table_ids = [$table->id];
                $booking->save();
            }
        }

        return view("app.restaurant.book", compact([
            "restaurant",
            "booking",
            "section",
        ]));
    }
}
