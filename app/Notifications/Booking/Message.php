<?php

namespace App\Notifications\Booking;

use App\Models\BookingMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Message extends Notification
{
    use Queueable;

    public $bookingMessage;

    /**
     * Create a new notification instance.
     *
     * @param BookingMessage $bookingMessage
     */
    public function __construct(BookingMessage $bookingMessage)
    {
        $this->bookingMessage = $bookingMessage;
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
        // default link to be used by person who booked
        $link = "bookings/" . $this->bookingMessage->booking->id;

        if($this->bookingMessage->created_by === $this->bookingMessage->booking->booked_by){
            // the author of the message made the booking, send the notification to restaurant staff
            $link = "restaurants/" . $this->bookingMessage->booking->restaurant->id . "/bookings/" . $this->bookingMessage->booking->id;
        }

        return [
            'title' => $this->bookingMessage->booking->restaurant->name,
            'text' => $this->bookingMessage->author . " sent you a message regarding booking #" . $this->bookingMessage->booking->id,
            'link' => $link,
        ];
    }
}
