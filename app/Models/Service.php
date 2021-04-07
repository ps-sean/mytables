<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = "mysql";

    const DAYS_OF_WEEK = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

    protected $fillable = [
        "day",
        "title",
        "description",
        "start",
        "finish",
        "last_booking"
    ];

    public function __toString()
    {
        return $this->title;
    }
}
