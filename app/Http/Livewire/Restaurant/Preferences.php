<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class Preferences extends Component
{
    public $restaurant;

    protected $rules = [
        "restaurant.table_confirmation" => "required",
        "restaurant.interval" => "numeric|min:5|max:90",
        "restaurant.booking_timeframe.tables" => "numeric|min:0",
        "restaurant.booking_timeframe.minutes" => "numeric|min:0|max:90",
        "restaurant.turnaround_time" => "numeric|min:0|max:90",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function render()
    {
        return view('livewire.restaurant.preferences');
    }

    public function submit()
    {
        $this->validate();

        $this->restaurant->save();

        $this->emitSelf("saved");
    }
}
