<?php

namespace App\Listeners;

use App\Events\BookingMessageCreated;
use App\Notifications\Booking\Message;
use App\Notifications\Restaurant\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendBookingMessageNotification
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
     * @param  BookingMessageCreated  $event
     * @return void
     */
    public function handle(BookingMessageCreated $event)
    {
        // default recipient to person who created the booking
        $users = $event->bookingMessage->booking->booker;

        if($event->bookingMessage->created_by === $event->bookingMessage->booking->booked_by){
            // the author of the message made the booking, send the notification to restaurant staff
            $users = $event->bookingMessage->booking->restaurant->staff;
        }

        Notification::send($users, new Message($event->bookingMessage));
    }
}
