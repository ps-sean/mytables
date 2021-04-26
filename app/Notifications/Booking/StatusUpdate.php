<?php

namespace App\Notifications\Booking;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusUpdate extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
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
        $link = "/bookings/" . $this->booking->id;
        $text = "Your booking #" . $this->booking->id . " has been updated.";

        if($this->booking->status === "cancelled"){
            // only the user can update the status to cancelled
            $link = "restaurants/" . $this->booking->restaurant->id . "/bookings/" . $this->booking->id;
            $text = $this->booking->name . " cancelled their booking #" . $this->booking->id;
        }

        return [
            'title' => $this->booking->restaurant->name,
            'text' => $text,
            'link' => $link,
        ];
    }
}
