<?php

namespace App\Livewire\Restaurant;

use App\Models\Restaurant;
use Carbon\Carbon;
use Livewire\Component;

class ServiceExceptions extends Component
{
    public $exceptions, $restaurant, $openDate, $newDate, $bookings;

    protected $rules = [
        "exceptions.*.service_date" => "required",
        "exceptions.*.restaurant_id" => "required",
        "exceptions.*.title" => "required_if:exceptions.*.closed,false",
        "exceptions.*.description" => "",
        "exceptions.*.start" => "required_if:exceptions.*.closed,false|exclude_if:exceptions.*.closed,true|nullable|date_format:H:i",
        "exceptions.*.finish" => "required_if:exceptions.*.closed,false|exclude_if:exceptions.*.closed,true|nullable|date_format:H:i",
        "exceptions.*.last_booking" => "required_if:exceptions.*.closed,false|exclude_if:exceptions.*.closed,true|nullable|date_format:H:i",
        "exceptions.*.closed" => "boolean",
    ];

    protected $listeners = ["service_exceptions" => 'mount'];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->exceptions = $restaurant->service_exceptions()
            ->whereDate("service_date", ">=", Carbon::now())
            ->get();

        if($this->exceptions->count()){
            $this->openDate = $this->exceptions->sortBy("service_date")->first()->service_date->format("Y-m-d");
            $this->bookings = $restaurant->bookings()
                ->whereDate("booked_at", $this->openDate)
                ->whereNotIn("status", ["cancelled", "rejected", "no show"])
                ->get();
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
                        "start" => Carbon::parse($service->start)->format("H:i"),
                        "finish" => Carbon::parse($service->finish)->format("H:i"),
                        "last_booking" => Carbon::parse($service->last_booking)->format("H:i"),
                        "closed" => 0,
                    ]));
                }
            } else {
                $this->exceptions->push($this->restaurant->service_exceptions()->make([
                    "closed" => 1,
                    "service_date" => Carbon::parse($this->newDate),
                ]));
            }

            $this->openDate = $this->exceptions->last()->service_date->format("Y-m-d");
            $this->bookings = $this->restaurant->bookings()
                ->whereDate("booked_at", $this->openDate)
                ->whereNotIn("status", ["cancelled", "rejected", "no show"])
                ->get();

            $this->newDate = null;
        }
    }

    public function changeOpenDate($date)
    {
        $this->openDate = $date;
        $this->bookings = $this->restaurant->bookings()
            ->whereDate("booked_at", $this->openDate)
            ->whereNotIn("status", ["cancelled", "rejected", "no show"])
            ->get();
    }

    public function removeException($index)
    {
        $this->exceptions->forget($index);

        if($this->exceptions->where("service_date", $this->openDate)->count() < 1){
            $this->exceptions->push($this->restaurant->service_exceptions()->make([
                "service_date" => $this->openDate,
                "closed" => 1,
            ]));
        }
    }

    public function removeExceptions()
    {
        foreach($this->exceptions->where("service_date", Carbon::parse($this->openDate)) as $index => $exception){
            $this->exceptions->forget($index);
        }

        if($this->exceptions->count()){
            $this->openDate = $this->exceptions->sortBy("service_date")->first()->service_date->format("Y-m-d");
            $this->bookings = $this->restaurant->bookings()
                ->whereDate("booked_at", $this->openDate)
                ->whereNotIn("status", ["cancelled", "rejected", "no show"])
                ->get();
        } else {
            $this->openDate = null;
            $this->bookings = null;
        }


    }

    public function submit()
    {
        $this->validate();

        $ids = $this->exceptions->whereNotNull("id")->pluck("id");

        $this->restaurant->service_exceptions()->whereNotIn("id", $ids)->delete();

        foreach($this->exceptions as $exception){
            $exception->save();
        }

        $this->dispatch("saved");
    }

    public function addService()
    {
        $this->exceptions->push($this->restaurant->service_exceptions()->make([
            "service_date" => $this->openDate,
            "closed" => 0,
        ]));
    }
}
