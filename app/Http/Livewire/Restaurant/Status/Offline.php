<?php

namespace App\Http\Livewire\Restaurant\Status;

use App\Models\Restaurant;
use Livewire\Component;

class Offline extends Component
{
    public $restaurant, $status;
    public $offlineConfirmation = false;

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->status = strtolower($this->restaurant->status->text);
    }

    public function render()
    {
        return view('livewire.restaurant.status.offline');
    }

    public function goLive()
    {
        $this->status = "live";
        $this->restaurant->status = $this->status;
        $this->restaurant->save();

        $this->emit('statusUpdated');
    }

    public function goOffline()
    {
        $this->status = "offline";
        $this->restaurant->status = $this->status;
        $this->restaurant->save();

        $this->emit('statusUpdated');

        $this->offlineConfirmation = false;
    }
}
