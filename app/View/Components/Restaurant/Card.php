<?php

namespace App\View\Components\Restaurant;

use App\Models\Restaurant;
use Illuminate\View\Component;

class Card extends Component
{
    public $restaurant;

    /**
     * Create a new component instance.
     *
     * @param Restaurant $restaurant
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.restaurant.card');
    }
}
