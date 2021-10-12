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
        "restaurant.booking_timeframe.covers" => "numeric|min:0",
        "restaurant.booking_timeframe.minutes" => "numeric|min:0|max:90",
        "restaurant.turnaround_time" => "numeric|min:0|max:90",
        "restaurant.show_days" => "numeric|min:1",
        "restaurant.no_show_fee" => "numeric|min:0",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function updatedRestaurantNoShowFee($value)
    {
        $this->restaurant->no_show_fee = number_format((float)$value, 2);
    }

    public function render()
    {
        return view('livewire.restaurant.preferences');
    }

    public function submit()
    {
        $this->validate();

        if(0 < $this->restaurant->no_show_fee && $this->restaurant->no_show_fee < 5){
            $this->addError("restaurant.no_show_fee", "The minimum pre-authorisation fee is £5.");
            return;
        }

        $this->restaurant->save();

        $this->emitSelf("saved");
    }
}
