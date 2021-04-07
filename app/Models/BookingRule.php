<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRule extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $fillable = [
        "max_covers",
        "minutes",
    ];
}
