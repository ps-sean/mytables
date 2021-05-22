<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;

    public $restaurant;

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function render()
    {
        $reviews = $this->restaurant->reviews()->orderBy("created_at", "DESC")->paginate(3);

        return view('livewire.restaurant.reviews', compact(["reviews"]));
    }
}
