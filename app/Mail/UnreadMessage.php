<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnreadMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient;
    public $bookings = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->recipient = $email->details;
        $this->bookings = array_unique($email->bookings);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("You have unread messages")
            ->markdown('emails.booking.unread_message');
    }
}
