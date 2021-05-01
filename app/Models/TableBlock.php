<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableBlock extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $casts = [
        "tables" => "json",
        "start_date" => "datetime:Y-m-d\TH:i",
        "end_date" => "datetime:Y-m-d\TH:i",
    ];

    protected $fillable = [
        "start_date",
        "end_date",
        "tables",
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
