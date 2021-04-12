<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function columns()
    {
        $start = Carbon::parse(date("Y-m-d " . $this->start));
        $finish = Carbon::parse(date("Y-m-d " . $this->finish));

        if($finish->lessThan($start)){
            $finish->addDay();
        }

        $length = $start->diffInMinutes($finish);

        return $length/$this->restaurant->booking_timeframe['minutes'];
    }
}
