<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class Preferences extends Component
{
    public $restaurant;
    public $lastBookingMinutes = 0;
    public $lastBookingMultiplier = 1;
    public $bookingConfirmation = "automatic";
    public $bookingTimeframe = [
        "tables" => 0,
        "minutes" => 0
    ];

    protected $rules = [
        "lastBookingMinutes" => "required|min:0|max:90",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;

        $this->bookingConfirmation = $restaurant->table_confirmation;
        $this->bookingTimeframe = $restaurant->booking_timeframe;

        if ($this->lastBookingMinutes !== 0 && $this->lastBookingMinutes % 60 === 0){
            $this->lastBookingMinutes = $this->lastBookingMinutes/60;
            $this->lastBookingMultiplier = 60;
        }
    }

    public function render()
    {
        return view('livewire.restaurant.preferences');
    }

    public function submit()
    {
        $this->validate();

        $this->restaurant->table_confirmation = $this->bookingConfirmation;
        $this->restaurant->booking_timeframe = $this->bookingTimeframe;

        $this->restaurant->save();

        $this->emitSelf("saved");
    }
}
