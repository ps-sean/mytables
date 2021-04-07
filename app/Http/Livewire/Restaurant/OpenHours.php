<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use App\Models\Service;
use Livewire\Component;

class OpenHours extends Component
{
    public $restaurant;

    protected $rules = [
        "restaurant.open_hours.Mon.open" => "",
        "restaurant.open_hours.Mon.close" => "",
        "restaurant.open_hours.Tue.open" => "",
        "restaurant.open_hours.Tue.close" => "",
        "restaurant.open_hours.Wed.open" => "",
        "restaurant.open_hours.Wed.close" => "",
        "restaurant.open_hours.Thu.open" => "",
        "restaurant.open_hours.Thu.close" => "",
        "restaurant.open_hours.Fri.open" => "",
        "restaurant.open_hours.Fri.close" => "",
        "restaurant.open_hours.Sat.open" => "",
        "restaurant.open_hours.Sat.close" => "",
        "restaurant.open_hours.Sun.open" => "",
        "restaurant.open_hours.Sun.close" => "",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function render()
    {
        return view('livewire.restaurant.open-hours');
    }

    public function submit()
    {
        $this->restaurant->save();

        $this->emitSelf("saved");
    }
}
