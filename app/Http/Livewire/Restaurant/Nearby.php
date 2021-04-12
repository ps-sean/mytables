<?php

namespace App\Http\Livewire\Restaurant;

use App\Http\Controllers\SearchController;
use Livewire\Component;

class Nearby extends Component
{
    public $restaurants;
    public $limit = 6;

    public function mount()
    {
        $this->restaurants = collect([]);
    }

    public function render()
    {
        return view('livewire.restaurant.nearby');
    }

    public function showMore()
    {
        $this->limit += 6;
    }

    public function load($lat, $lng)
    {
        $this->restaurants = SearchController::byRadius($lat, $lng)
            ->sortBy("distance");
    }
}
