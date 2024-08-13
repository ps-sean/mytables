<?php

namespace App\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class PaymentMethod extends Component
{
    public $restaurant;

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function render()
    {
        return view('livewire.restaurant.payment-method');
    }

    public function addCard($paymentMethod)
    {
        $this->restaurant->updateDefaultPaymentMethod($paymentMethod);

        $this->dispatch("saved")->self();
    }
}
