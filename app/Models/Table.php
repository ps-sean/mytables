<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = "mysql";

    protected $fillable = [
        "name",
        "seats"
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
