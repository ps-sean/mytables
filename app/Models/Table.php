<?php

namespace App\Models;

use App\Traits\History;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Table extends Model
{
    use HasFactory, SoftDeletes, History;

    protected $connection = "mysql";

    protected $fillable = [
        "name",
        "seats",
        "bookable",
    ];

    public function __toString()
    {
        $string = $this->name;

        if($this->table_group){
            $string .= " - " . $this->table_group;
        }

        return $string;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function table_group()
    {
        return $this->belongsTo(TableGroup::class);
    }
}
