<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show(Booking $booking)
    {
        $restaurant = $booking->restaurant;

        return view("bookings.show", compact([
            "booking",
            "restaurant",
        ]));
    }
}
