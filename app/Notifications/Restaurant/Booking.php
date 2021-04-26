<?php

namespace App\Notifications\Restaurant;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking as RestaurantBooking;

class Booking extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     *
     * @param RestaurantBooking $booking
     */
    public function __construct(RestaurantBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->booking->restaurant->name,
            'text' => $this->booking->name . " booked a table for " . $this->booking->covers . " on " . $this->booking->booked_at->toDayDateTimeString() . ".",
            'link' => "restaurants/" . $this->booking->restaurant->id . "/bookings?date=" . $this->booking->booked_at->format("Y-m-d") . "&search=" . $this->booking->name,
        ];
    }
}
