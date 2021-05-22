<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
        "booking_id",
        "user_id"
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->hasOneThrough(Restaurant::class, Booking::class, "1", "2", "3", "4");
    }
}
