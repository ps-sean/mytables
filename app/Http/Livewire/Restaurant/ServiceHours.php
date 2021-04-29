<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Livewire\Component;

class ServiceHours extends Component
{
    public $restaurant, $services, $scheduleDate;
    public $open = false;
    public $schedule = false;
    public $openDay = "Mon";

    protected $rules = [
        "services.*.title" => "required",
        "services.*.description" => "",
        "services.*.day" => "required",
        "services.*.start" => "required|date_format:H:i",
        "services.*.finish" => "required|date_format:H:i",
        "services.*.last_booking" => "required|date_format:H:i",
        "scheduleDate" => "",
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

    public function updatedSchedule($value)
    {
        if(!$value){
            $this->scheduleDate = null;
        }
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

        // check if the changes are to be scheduled
        if(!empty($this->scheduleDate)){
            // add exceptions for all the current services until the date provided
            $scheduledDate = Carbon::parse($this->scheduleDate)->subDay();

            $period = CarbonPeriod::create(Carbon::now()->startOfDay(), CarbonInterval::day(), $scheduledDate);

            foreach($period as $day){
                // check for current exceptions
                if(!$this->restaurant->service_exceptions()->whereDate("service_date", $day)->count()){
                    // load services for this day
                    $services = $this->restaurant->services()->where("day", $day->shortEnglishDayOfWeek)->get();

                    if($services->count()){
                        foreach($services as $service){
                            $this->restaurant->service_exceptions()->create([
                                "service_date" => Carbon::parse($day),
                                "title" => $service->title,
                                "description" => $service->description,
                                "start" => Carbon::parse($service->start)->format("H:i"),
                                "finish" => Carbon::parse($service->finish)->format("H:i"),
                                "last_booking" => Carbon::parse($service->last_booking)->format("H:i"),
                            ]);
                        }
                    } else {
                        // no services for this date, close it down
                        $this->restaurant->service_exceptions()->create([
                            "service_date" => $day,
                            "closed" => 1,
                        ]);
                    }
                }
            }

            $this->emit("service_exceptions", $this->restaurant->id);
            $this->schedule = false;
        }

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
