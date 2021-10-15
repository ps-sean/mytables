<?php

namespace App\Models;

use App\Traits\History;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Table extends Model
{
    use HasFactory, SoftDeletes, History, HasJsonRelationships;

    protected $connection = "mysql";

    protected $fillable = [
        "name",
        "seats",
        "bookable",
    ];

    public function __toString()
    {
        $string = $this->name;

        if($this->section){
            $string .= " - " . $this->section;
        }

        return $string;
    }

    public function section()
    {
        return $this->belongsTo(RestaurantSection::class, "restaurant_section_id");
    }

    public function bookings()
    {
        return $this->hasManyJson(Booking::class, "table_ids");
    }
}
