<?php

namespace App\Livewire\Restaurant;

use App\Models\Restaurant;
use Carbon\Carbon;
use Livewire\Component;

class OpenHoursExceptions extends Component
{
    public $exceptions, $restaurant;
    public $open = false;
    public $newException = [];

    protected $rules = [
        "newException.open_date" => "",
        "newException.open" => "",
        "newException.close" => "",
        "exceptions.*.open_date" => "required",
        "exceptions.*.open" => "",
        "exceptions.*.close" => "",
        "exceptions.*.restaurant_id" => "required",
    ];

    protected $listeners = ["openOpenHoursExceptions" => "open"];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->exceptions = $restaurant->open_hours_exceptions()->orderBy("open_date")->get();
    }

    public function render()
    {
        return view('livewire.restaurant.open-hours-exceptions');
    }

    public function open()
    {
        $this->open = true;
    }

    public function addException()
    {
        $this->validate([
            "newException.open_date" => "required|date|after_or_equal:today",
            "newException.open" => "",
            "newException.close" => "",
        ]);

        $this->exceptions->push($this->restaurant->open_hours_exceptions()->make($this->newException));

        $this->newException = [];
    }

    public function removeException($index)
    {
        $this->exceptions->forget($index);
    }

    public function submit()
    {
        $this->validate();

        $ids = $this->exceptions->whereNotNull("id")->pluck("id");

        $this->restaurant->open_hours_exceptions()->whereNotIn("id", $ids)->delete();

        foreach($this->exceptions as $exception){
            $exception->save();
        }

        $this->dispatch("saved");
    }
}
