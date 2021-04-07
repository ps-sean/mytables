<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenHoursException extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $dates = [
        "open_date"
    ];

    protected $fillable = [
        "open_date",
        "open",
        "close",
    ];
}
