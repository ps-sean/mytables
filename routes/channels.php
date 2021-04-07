<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.Restaurant.{id}', function($user, $id){
    return $user->restaurants->contains(\App\Models\Restaurant::find($id));
});

Broadcast::channel('App.Models.Booking.{id}', function($user, $id){
    $booking = \App\Models\Booking::find($id);

    if($booking){
        if($user->id === $booking->booked_by){
            return true;
        }

        if($user->restaurants->contains($booking->restaurant)){
            return true;
        }
    }

    return false;
});
