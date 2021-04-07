<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class Status extends Component
{
    public $restaurant;

    protected $listeners = ['statusUpdated' => 'refreshRestaurant'];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function render()
    {
        return view('livewire.restaurant.status');
    }

    public function refreshRestaurant()
    {
        $this->restaurant = Restaurant::find($this->restaurant->id);
    }
}
