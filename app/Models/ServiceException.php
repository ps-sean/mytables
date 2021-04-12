<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceException extends Service
{
    protected $dates = [
        "service_date"
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
