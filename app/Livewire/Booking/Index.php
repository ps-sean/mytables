<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $bookingVar;

    public function mount($bookings)
    {
        $this->bookingVar = $bookings;
    }

    public function render()
    {
        return view('livewire.booking.index', [
            'bookings' => auth()->user()->{$this->bookingVar}()->paginate(15)
        ]);
    }
}
