<?php

namespace App\Listeners;

use App\Events\BookingStatusUpdated;
use App\Notifications\Booking\StatusUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AnnounceBookingStatusUpdate
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
    public function handle(BookingStatusUpdated $event)
    {
        $users = $event->booking->booker;
        $mailTo = $event->booking->email;

        if($event->booking->status === "cancelled"){
            $users = $event->booking->restaurant->staff;
            $mailTo = $event->booking->restaurant->email;
        }

        Mail::to($mailTo)->send(new \App\Mail\Booking\StatusUpdate($event->booking));
        Notification::send($users, new StatusUpdate($event->booking));
    }
}
