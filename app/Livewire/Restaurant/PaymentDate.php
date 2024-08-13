<?php

namespace App\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class PaymentDate extends Component
{
    public $restaurant;

    protected $rules = [
        "restaurant.billing_date" => "numeric|min:1|max:28"
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function render()
    {
        return view('livewire.restaurant.payment-date');
    }

    public function submit()
    {
        $this->validate();

        $this->restaurant->save();

        $this->dispatch("saved")->self();
    }
}
