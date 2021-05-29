<?php

namespace App\Http\Livewire\Restaurant\Status;

use App\Mail\Restaurant\StatusUpdated;
use App\Models\Restaurant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Confirm extends Component
{
    public $restaurants;
    public $status = null;
    public $restaurant_id = null;

    public function mount(Collection $restaurants)
    {
        $this->restaurants = $restaurants->keyBy('id');
    }

    public function render()
    {
        return view('livewire.restaurant.status.confirm');
    }

    public function save()
    {
        $restaurant = Restaurant::find($this->restaurant_id);

        $key = $this->restaurants->search(function($r) use ($restaurant){
            return $r->id === $restaurant->id;
        });

        $this->restaurants->forget($key);

        $restaurant->status = $this->status;
        $restaurant->save();

        Mail::to($restaurant->email)->queue(new StatusUpdated($restaurant));

        $this->status = null;

    }
}
