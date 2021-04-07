<?php

namespace App\Mail\Booking;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Your booking at " . $this->booking->restaurant;
        $replyTo = $this->booking->restaurant->email;
        $view = "emails.booking.status_update";

        if($this->booking->status === "cancelled"){
            // only the user who booked can cancel the booking, send the email to the restaurant
            $subject = $this->booking->name . " cancelled their booking #" . $this->booking->id;
            $replyTo = $this->booking->email;
            $view = "emails.booking.cancelled";
        }

        return $this->subject($subject)
            ->replyTo($replyTo)
            ->markdown($view);
    }
}
