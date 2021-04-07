<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableGroup extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $fillable = ["name"];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function __toString()
    {
        return $this->name;
    }
}
