<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Carbon\Carbon;
use Livewire\Component;

class ServiceExceptions extends Component
{
    public $exceptions, $restaurant, $openDate, $newDate;

    protected $rules = [
        "exceptions.*.service_date" => "required",
        "exceptions.*.restaurant_id" => "required",
        "exceptions.*.title" => "required",
        "exceptions.*.description" => "",
        "exceptions.*.start" => "required|date_format:H:i",
        "exceptions.*.finish" => "required|date_format:H:i",
        "exceptions.*.last_booking" => "required|date_format:H:i",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->exceptions = $restaurant->service_exceptions()
            ->whereDate("service_date", ">=", Carbon::now())
            ->get();

        if($this->exceptions->count()){
            $this->openDate = $this->exceptions->first()->service_date->format("Y-m-d");
        }
    }

    public function render()
    {
        return view('livewire.restaurant.service-exceptions');
    }

    public function addException()
    {
        if(empty($this->newDate)){
            $this->newDate = Carbon::now()->format("Y-m-d");
        } else {
            $this->validate([
                "newDate" => "required|date|after_or_equal:today"
            ]);

            // load the normal services for that date
            $services = $this->restaurant->services()->where("day", Carbon::parse($this->newDate)->shortEnglishDayOfWeek)->get();

            if($services->count()){
                foreach($services as $service){
                    $this->exceptions->push($this->restaurant->service_exceptions()->make([
                        "service_date" => Carbon::parse($this->newDate),
                        "title" => $service->title,
                        "description" => $service->description,
                        "start" => $service->start,
                        "finish" => $service->finish,
                        "last_booking" => $service->last_booking,
                    ]));
                }
            } else {
                $this->exceptions->push($this->restaurant->service_exceptions()->make([
                    "service_date" => Carbon::parse($this->newDate),
                ]));
            }

            $this->openDate = $this->exceptions->last()->service_date->format("Y-m-d");

            $this->newDate = null;
        }
    }

    public function changeOpenDate($date)
    {
        $this->openDate = $date;
    }

    public function removeException($index)
    {
        $this->exceptions->forget($index);
    }

    public function submit()
    {
        $this->validate();

        $ids = $this->exceptions->pluck("id");

        $this->restaurant->service_exceptions()->whereNotIn("id", $ids)->delete();

        foreach($this->exceptions as $exception){
            $exception->save();
        }

        $this->emit("saved");
    }

    // TODO add extra service

    // TODO block out day
}
