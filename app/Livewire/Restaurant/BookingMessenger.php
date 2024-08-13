<?php

namespace App\Livewire\Restaurant;

use App\Models\Booking;
use App\Models\BookingMessage;
use App\Notifications\Booking\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingMessenger extends Component
{
    public $message, $messages, $booking;

    protected $rules = [
        "message" => "required"
    ];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;

        $this->updateMessages();
    }

    public function getListeners()
    {
        return [
            "echo-private:App.Models.Booking." . $this->booking->id . ".message,BookingMessageCreated" => 'updateMessages',
        ];
    }

    public function render()
    {
        return view('livewire.restaurant.booking-messenger');
    }

    public function updateMessages()
    {
        // mark notifications for this booking as read
        foreach(Auth::user()->unreadNotifications()->where("type", Message::class)->get() as $notification)
        {
            if(in_array($notification->data['link'], [
                "restaurants/" . $this->booking->restaurant->id . "/bookings/" . $this->booking->id,
                "bookings/" . $this->booking->id,
            ])){
                $notification->markAsRead();
                $this->dispatch("updateCount");
            }
        }

        $this->messages = $this->booking->messages;

        foreach($this->messages as $message)
        {
            if(empty($message->read_at) && !$message->me()){
                // mark messages as read
                $message->read_at = Carbon::now();
                $message->save();
            }
        }

        $this->dispatch("new-message")->self();
    }

    public function submit()
    {
        $bm = $this->booking->messages()->create([
            "message" => $this->message
        ]);

        $this->message = "";
    }
}
