<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Stripe\Account;

class RestaurantController extends Controller
{
    public function status($status)
    {
        $restaurants = Restaurant::where("status", $status)->orderBy("created_at")->get();

        return view("restaurant.status", compact([
            "status",
            "restaurants"
        ]));
    }

    public function manage(Restaurant $restaurant)
    {
        if(empty($restaurant->stripe_id)){
            $restaurant->linkAccount();
        }

        return view("restaurant.manage.show", compact(["restaurant"]));
    }

    public function bookingsSelect()
    {
        $restaurants = Auth::user()->restaurants;

        if(!$restaurants->count()){
            abort(403);
        }

        if($restaurants->count() === 1){
            return redirect(route("restaurant.bookings", $restaurants->first()));
        }

        return view("restaurant.bookings_select");
    }

    public function bookings(Request $request, Restaurant $restaurant)
    {
        if(!empty($request->date)){
            $date = Carbon::parse($request->date);
        } else {
            $date = Carbon::now();
        }

        return view("restaurant.bookings", compact([
            "restaurant",
            "date"
        ]));
    }

    public function booking(Restaurant $restaurant, Booking $booking)
    {
        if($booking->restaurant_id !== $restaurant->id){
            abort(404);
        }

        return view("bookings.show", compact([
            "restaurant",
            "booking"
        ]));
    }

    public function redirectWithName(Restaurant $restaurant)
    {
        return redirect()->route("restaurant.show", [$restaurant, $restaurant->name]);
    }

    public function show(Restaurant $restaurant, $name = null)
    {
        if(strtolower($restaurant->status->text) !== "live"){
            if (!$restaurant->staff->contains(Auth::user())) {
                return view("restaurant.offline");
            }
        }

        $specialHours = $restaurant->open_hours_exceptions()
            ->whereDate("open_date", "<=", \Carbon\Carbon::now()->addWeeks(2))
            ->orderBy("open_date")
            ->get();

        return view("restaurant.show", compact([
            "restaurant",
            "specialHours",
        ]));
    }

    public function verifyEmail(Request $request, Restaurant $restaurant)
    {
        if(!$request->hasValidSignature()){
            abort(401);
        }

        $restaurant->email_verified_at = Carbon::now();
        $restaurant->save();

        Notification::send(Team::find(1)->allUsers(), new \App\Notifications\Restaurant\Status($restaurant));

        return view("restaurant.email_verified");
    }
}
