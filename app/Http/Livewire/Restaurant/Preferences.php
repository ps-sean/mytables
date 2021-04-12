<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class Preferences extends Component
{
    public $restaurant;
    public $bookingConfirmation = "automatic";
    public $bookingTimeframe = [
        "tables" => 0,
        "minutes" => 0
    ];
    public $bookingTurnaround = 0;

    protected $rules = [
        "bookingConfirmation" => "required",
        "bookingTimeframe.tables" => "min:0",
        "bookingTimeframe.minutes" => "min:0|max:90",
        "bookingTurnaround" => "min:0|max:90",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;

        $this->bookingConfirmation = $restaurant->table_confirmation;
        $this->bookingTimeframe = $restaurant->booking_timeframe;

        $this->bookingTurnaround = $restaurant->turnaround_time;
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
        $this->restaurant->turnaround_time = $this->bookingTurnaround;

        $this->restaurant->save();

        $this->emitSelf("saved");
    }
}
