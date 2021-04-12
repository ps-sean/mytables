<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class TableGroups extends Component
{
    public $restaurant, $groups;

    protected $rules = [
        "groups.*.name" => "required"
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->groups = $restaurant->table_groups;
    }

    public function render()
    {
        return view('livewire.restaurant.table-groups');
    }

    public function addGroup()
    {
        $this->groups->push($this->restaurant->table_groups()->make());
    }

    public function removeGroup($key)
    {
        $this->groups->forget($key);
    }

    public function submit()
    {
        $this->validate();

        $existingGroups = $this->groups->whereNotNull("id")->pluck("id");

        $this->restaurant->table_groups()->whereNotIn("id", $existingGroups)->delete();

        foreach($this->groups as $group){
            $group->restaurant_id = $this->restaurant->id;

            $group->save();
        }

        $this->emitSelf("saved");
        $this->emit("groups-update");
    }
}
