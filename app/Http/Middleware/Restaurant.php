<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Restaurant
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
        $r = $request->route()->parameter("restaurant");

        if(!empty($r->id)){
            $r = $r->id;
        }

        $restaurant = \App\Models\Restaurant::find($r);

        if(!$restaurant){
            abort(404, "Restaurant with this ID could not be found");
        }

        if(!Auth::user()->restaurants->contains($restaurant)){
            abort(403, "You do not have permission to manage this restaurant");
        }

        return $next($request);
    }
}
