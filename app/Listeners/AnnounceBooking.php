<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Mail\Booking\Created;
use App\Models\User;
use App\Notifications\Restaurant\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AnnounceBooking
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BookingCreated $event)
    {
        if(empty($event->booking->booked_by)){
            // check if we have a user matching this email address
            $user = User::where("email", $event->booking->email)->first();

            if($user){
                $event->booking->booked_by = $user->id;
                $event->booking->save();
            }
        }

        Mail::to($event->booking->restaurant->email)->queue(new Created($event->booking));
        Notification::send($event->booking->restaurant->staff, new Booking($event->booking));
    }
}
