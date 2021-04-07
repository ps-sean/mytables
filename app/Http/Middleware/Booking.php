<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Booking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $b = $request->route()->parameter("booking");

        if(!empty($b->id)){
            $b = $b->id;
        }

        $booking = \App\Models\Booking::find($b);

        if(!$booking){
            abort(404, "Booking with this ID could not be found");
        }

        if(!Auth::user()->bookings->contains($booking)){
            abort(403, "You do not have permission to view this booking");
        }

        return $next($request);
    }
}
