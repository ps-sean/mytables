<?php

namespace App\Http\Livewire\Restaurant;

use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Livewire\Component;
use Stevebauman\Location\Facades\Location;

class Nearby extends Component
{
    public $restaurants;
    public $limit = 6;

    public function mount()
    {
        $this->restaurants = collect([]);

        if($position = Location::get("82.29.99.99")){
           $this->restaurants = SearchController::byRadius($position->latitude, $position->longitude)
               ->sortBy("distance");
        }
    }

    public function render()
    {
        return view('livewire.restaurant.nearby');
    }

    public function showMore()
    {
        $this->limit += 6;
    }
}
