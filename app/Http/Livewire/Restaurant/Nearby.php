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

    public function load($position)
    {
        $position = (object)$position;

        $this->restaurants = SearchController::byRadius($position->latitude, $position->longitude)
            ->sortBy("distance");
    }
}
