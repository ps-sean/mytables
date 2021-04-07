<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Mail\Booking\Created;
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
        Mail::to($event->booking->restaurant->email)->send(new Created($event->booking));
        Notification::send($event->booking->restaurant->staff, new Booking($event->booking));
    }
}
