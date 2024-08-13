<?php

namespace App\Livewire\Restaurant;

use App\Models\Restaurant;
use Livewire\Component;

class BlockTables extends Component
{
    public $restaurant, $blocks;

    protected $rules = [
        "blocks.*.restaurant_id" => "required",
        "blocks.*.start_date" => "required",
        "blocks.*.end_date" => "required",
        "blocks.*.tables" => ""
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->blocks = $restaurant->tableBlocks;
    }

    public function render()
    {
        return view('livewire.restaurant.block-tables');
    }

    public function addBlock()
    {
        $this->blocks->push($this->restaurant->tableBlocks()->make([
            "tables" => []
        ]));
    }

    public function removeBlock($index)
    {
        $this->blocks->forget($index);
    }

    public function submit()
    {
        $this->validate();

        $ids = $this->blocks->pluck("id");

        $this->restaurant->tableBlocks()->whereNotIn("id", $ids)->delete();

        foreach($this->blocks as $block){
            $block->save();
        }

        $this->dispatch("saved")->self();
    }
}
