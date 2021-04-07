<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\Booking\StatusUpdate;

class AutoConfirmBooking
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
     * @param  BookingCreated  $event
     * @return void
     */
    public function handle(BookingCreated $event)
    {
        if($event->booking->status === "confirmed" || $event->booking->restaurant->table_confirmation === "automatic"){
            if($event->booking->status !== "confirmed"){
                $event->booking->status = "confirmed";
                $event->booking->save();
            }

            // send the confirmation email
            Mail::to($event->booking->email)->send(new StatusUpdate($event->booking));
        }
    }
}
