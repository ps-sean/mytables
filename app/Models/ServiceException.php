<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceException extends Service
{
    protected $casts = [
        "service_date" => "datetime",
        "start" => "datetime:H:i",
        "finish" => "datetime:H:i",
        "last_booking" => "datetime:H:i",
    ];

    protected $fillable = [
        "service_date",
        "title",
        "description",
        "start",
        "finish",
        "last_booking",
        "closed",
    ];
}
