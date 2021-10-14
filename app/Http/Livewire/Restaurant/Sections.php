<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class Sections extends Component
{
    public $restaurant, $sections;

    protected $rules = [
        "sections.*.name" => "required"
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->sections = $restaurant->sections;
    }

    public function render()
    {
        return view('livewire.restaurant.sections');
    }

    public function addSection()
    {
        $this->sections->push($this->restaurant->sections()->make());
    }

    public function removeSection($key)
    {
        $this->sections->forget($key);
    }

    public function submit()
    {
        $this->validate();

        $existingSections = $this->sections->whereNotNull("id")->pluck("id");

        $this->restaurant->sections()->whereNotIn("id", $existingSections)->delete();

        foreach($this->sections as $section){
            $section->restaurant_id = $this->restaurant->id;

            $section->save();
        }

        $this->emitSelf("saved");
        $this->emit("sections-update");
    }
}
