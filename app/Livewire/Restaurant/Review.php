<?php

namespace App\Livewire\Restaurant;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Review extends Component
{
    public $review, $booking;

    protected $rules = [
        "review.booking_id" => "required",
        "review.user_id" => "required",
        "review.price" => "min:1|max:5",
        "review.service" => "min:1|max:5",
        "review.product" => "min:1|max:5",
        "review.cleanliness" => "min:1|max:5",
        "review.overall" => "required|min:1|max:5",
        "review.title" => "required|max:255",
        "review.review" => "",
    ];

    public function mount(Booking $booking = null)
    {
        $this->review = Auth::user()->reviews()->make();

        if($booking){
            $this->booking = $booking;
            $this->review->booking_id = $booking->id;
        }
    }

    public function render()
    {
        return view('livewire.restaurant.review');
    }

    public function submit()
    {
        $this->validate();

        $this->review->save();

        $this->booking->refresh();

        $this->dispatch("saved")->self();
    }
}
