<?php

namespace App\Http\Livewire\Restaurant\Status;

use App\Models\Restaurant;
use Livewire\Component;

class Complete extends Component
{
    public $restaurant, $status;

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->status = strtolower($this->restaurant->status->text);
    }

    public function render()
    {
        return view('livewire.restaurant.status.complete');
    }

    public function goLive()
    {
        $this->status = "live";
        $this->restaurant->status = $this->status;
        $this->restaurant->save();

        $this->emit('statusUpdated');
    }
}
