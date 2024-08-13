<?php

namespace App\Livewire\Restaurant;

use App\Models\Restaurant;
use App\Models\User;
use Livewire\Component;

class Staff extends Component
{
    public $restaurant, $staff, $search;

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->staff = $restaurant->staff;
    }

    public function render()
    {
        return view('livewire.restaurant.staff');
    }

    public function removeStaff($index)
    {
        $this->staff->forget($index);
    }

    public function addStaff()
    {
        $this->resetErrorBag();

        $user = User::where("email", $this->search)->first();

        if(!$user){
            $this->addError("search", "No account found for email '" . $this->search . "'");
            return;
        }

        // check if this user already exists for this restaurant
        if($this->restaurant->staff->contains($user)){
            $this->addError("search", "A user with email '" . $this->search . "' already exists for your restaurant");
            return;
        }

        $this->staff->push($user);
        $this->dispatch("staffAdded")->self();
        $this->search = null;
    }

    public function submit()
    {
        $existingStaff = $this->staff->whereNotNull("id")->pluck("id");

        foreach($this->restaurant->staff as $staff){
            if(!$this->staff->contains($staff)){
                $this->restaurant->staff()->detach($staff->id);
            }
        }

        foreach($this->staff as $staff){
            if(!$this->restaurant->staff->contains($staff)){
                $this->restaurant->staff()->attach($staff->id);
            }
        }

        $this->dispatch("saved")->self();
    }
}
