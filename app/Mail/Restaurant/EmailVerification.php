<?php

namespace App\Mail\Restaurant;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;
    public $signedRoute;

    /**
     * Create a new message instance.
     *
     * @param Restaurant $restaurant
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->signedRoute = URL::temporarySignedRoute(
            'restaurant.verify_email', now()->addMinutes(15), ['restaurant' => $this->restaurant->id]
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.restaurant.email_verification');
    }
}
