<?php

namespace App\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;
use Mockery\Exception;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class Orders extends Component
{
    public function render()
    {
        return view('livewire.restaurant.orders');
    }
}
