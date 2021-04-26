<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use App\Models\Service;
use Livewire\Component;

class ServiceHours extends Component
{
    public $restaurant, $services;
    public $open = false;
    public $openDay = "Mon";

    protected $rules = [
        "services.*.title" => "required",
        "services.*.description" => "",
        "services.*.day" => "required",
        "services.*.start" => "required|date_format:H:i",
        "services.*.finish" => "required|date_format:H:i",
        "services.*.last_booking" => "required|date_format:H:i",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->services = $restaurant->services->sortBy("start");
    }

    public function render()
    {
        return view('livewire.restaurant.service-hours');
    }

    public function changeTab($day)
    {
        $this->openDay = $day;
    }

    public function addService($day)
    {
        $this->services->push(Service::make([
            "day" => $day
        ]));
    }

    public function removeService($index)
    {
        $this->services->forget($index);
    }

    public function submit()
    {
        $this->validate();

        $existingServices = $this->services->whereNotNull("id")->pluck("id");

        $this->restaurant->services()->whereNotIn("id", $existingServices)->delete();

        foreach($this->services as $service){
            $service->restaurant_id = $this->restaurant->id;

            $service->save();
        }

        $this->services = $this->services->sortBy("start");

        $this->emitSelf("saved");
    }
}
