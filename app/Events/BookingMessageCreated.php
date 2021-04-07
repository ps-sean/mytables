<?php

namespace App\Events;

use App\Models\BookingMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingMessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bookingMessage;

    /**
     * Create a new event instance.
     *
     * @param BookingMessage $bookingMessage
     */
    public function __construct(BookingMessage $bookingMessage)
    {
        $this->bookingMessage = $bookingMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.Booking.' . $this->bookingMessage->booking->id . '.message');
    }
}
