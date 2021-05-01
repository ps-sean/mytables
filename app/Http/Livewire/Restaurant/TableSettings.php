<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use App\Models\Table;
use Livewire\Component;

class TableSettings extends Component
{
    public $restaurant, $tables;
    public $show = false;
    public $groupModal = false;
    public $blockModal = false;

    protected $rules = [
        'tables.*.id' => '',
        'tables.*.name' => 'required',
        'tables.*.seats' => 'min:1',
        'tables.*.table_group_id' => '',
        'tables.*.bookable' => 'boolean',
    ];

    protected $listeners = ["groups-update" => "groupsUpdate"];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->tables = $restaurant->tables;
    }

    public function render()
    {
        return view('livewire.restaurant.table-settings');
    }

    public function groupsUpdate()
    {
        $this->restaurant->refresh();
    }

    public function addTable()
    {
        $table = new Table();

        $table->fill([
            "name" => "Table " . ($this->tables->count() + 1),
            "seats" => 2,
            "bookable" => 1,
        ]);

        $this->tables->push($table);
    }

    public function deleteTable($index)
    {
        $this->tables->forget($index);
    }

    public function toggleShow()
    {
        $this->show = !$this->show;
    }

    public function submit()
    {
        // delete any tables that are no longer present
        // get a list of ID's
        $existingTables = $this->tables->whereNotNull("id")->pluck("id");

        $this->restaurant->tables()->whereNotIn("id", $existingTables)->delete();

        foreach($this->tables as $table){
            $table->restaurant_id = $this->restaurant->id;

            if(empty($table->table_group_id)){
                $table->table_group_id = null;
            }

            $table->save();
        }

        $this->emitSelf("saved");
    }
}
