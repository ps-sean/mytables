<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\BookingRule;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Livewire\Component;

class BookingLength extends Component
{
    public $restaurant, $services, $booking_rules;
    public $open = false;

    protected $rules = [
        "booking_rules.*.max_covers" => "min:1|required",
        "booking_rules.*.minutes" => "min:15|required",
        "booking_rules.*.restaurant_id" => "required",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->services = $restaurant->services;
        $this->booking_rules = $restaurant->booking_rules;
    }

    public function render()
    {
        return view('livewire.restaurant.booking-length');
    }

    public function addRule()
    {
        $this->booking_rules->push($this->restaurant->booking_rules()->make([
            "max_covers" => $this->restaurant->max_booking_size(),
            "minutes" => 120
        ]));
    }

    public function removeRule($index)
    {
        $this->booking_rules->forget($index);
    }

    public function submit()
    {
        $ids = $this->booking_rules->whereNotNull("id")->pluck("id");

        $this->restaurant->booking_rules()->whereNotIn("id", $ids)->delete();

        foreach($this->booking_rules as $index => $rule){
            $rule->save();
        }

        $this->emitSelf("saved");
    }
}
