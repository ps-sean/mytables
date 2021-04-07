<?php

namespace App\Models;

use App\Events\BookingMessageCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BookingMessage extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $fillable = [
        "message"
    ];

    protected $dates = [
        "read_at"
    ];

    protected $dispatchesEvents = [
        'created' => BookingMessageCreated::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model){
            $model->created_by = Auth::user()->id;
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function me()
    {
        if($this->created_by === Auth::user()->id){
            return true;
        }

        if($this->booking->restaurant->staff->contains(Auth::user()) && $this->booking->restaurant->staff->contains($this->author) && ($this->booking->booked_by !== $this->created_by)){
            return true;
        }

        return false;
    }
}
