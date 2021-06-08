<?php

namespace App\Listeners;

use App\Events\CustomerUpdatedBooking;
use App\Notifications\Restaurant\Booking;
use App\Notifications\Restaurant\BookingUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class RestaurantBookingUpdate
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
    public function handle(CustomerUpdatedBooking $event)
    {
        Notification::send($event->booking->restaurant->staff, new BookingUpdated($event->booking));
    }
}
