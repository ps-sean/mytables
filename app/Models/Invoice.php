<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $dates = ["start", "end"];
    protected $casts = [
        "notes" => "json"
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
