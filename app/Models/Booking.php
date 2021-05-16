<?php

namespace App\Models;

use App\Events\BookingCreated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $casts = [
        "booked_at" => "datetime:Y-m-d\TH:i",
        "finish_at" => "datetime:Y-m-d\TH:i",
    ];

    protected $guarded = [
        "id"
    ];

    protected $dispatchesEvents = [
        'created' => BookingCreated::class,
    ];

    public function __toString()
    {
        return $this->name . " (" . $this->booked_at->toDayDateTimeString() . ")";
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tableNumber()
    {
        return $this->belongsTo(Table::class, "table_id");
    }

    public function messages()
    {
        return $this->hasMany(BookingMessage::class);
    }

    public function booker()
    {
        return $this->belongsTo(User::class, "booked_by");
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function setBookedAtAttribute($value)
    {
        if(!$value instanceof Carbon){
            $value = Carbon::parse(urldecode($value));
        }

        $this->attributes["booked_at"] = $value;

        // set this based on the number of people at the table, from restaurant settings
        $this->attributes["finish_at"] = $value->clone()->addMinutes($this->restaurant->checkBookingRule($this->covers)->minutes);
    }

    public function assignTable($group = "all")
    {
        $tablesUsed = collect([]);

        // there is a free timeslot, now check how many tables are booked in between the start and finish time
        $seatedBookings = $this->restaurant->bookings()->where(function($query){
            $query->where([
                ["booked_at", ">=", $this->booked_at->subMinutes($this->restaurant->turnaround_time)],
                ["booked_at", "<", $this->finish_at->addMinutes($this->restaurant->turnaround_time)],
            ]);
            $query->orWhere([
                ["finish_at", ">", $this->booked_at->subMinutes($this->restaurant->turnaround_time)],
                ["finish_at", "<=", $this->finish_at->addMinutes($this->restaurant->turnaround_time)],
            ]);
            $query->orWhere([
                ["booked_at", "<=", $this->booked_at->subMinutes($this->restaurant->turnaround_time)],
                ["finish_at", ">=", $this->finish_at->addMinutes($this->restaurant->turnaround_time)],
            ]);
        })
            ->whereIn("status", ["pending", "confirmed"])
            ->get();

        foreach($seatedBookings as $booking){
            if(!$tablesUsed->contains($booking->tableNumber)){
                $tablesUsed->push($booking->tableNumber);
            }
        }

        $blockedTables = [];

        $blocks = $this->restaurant->tableBlocks()
            ->where("start_date", "<", $this->finish_at)
            ->where("end_date", ">", $this->booked_at)
            ->get();

        foreach($blocks as $block){
            $blockedTables = array_merge($blockedTables, $block->tables);
        }

        // get the smallest table available that isn't being used
        $tables = $this->restaurant->tables()
            ->where("bookable", 1)
            ->where("seats", ">=", $this->covers)
            ->whereNotIn("id", $tablesUsed->whereNotNull("id")->pluck("id"))
            ->whereNotIn("id", $blockedTables)
            ->orderBy("seats");

        if($group != "all"){
            $tables->where("table_group_id", $group);
        }

        return $tables->first();
    }

    public function checkTime($group = "all")
    {
        if($this->exists){
            // booking already exists, check if the new table and/or time is available
            $tableBookings = $this->restaurant->bookings()->where(function($query){
                $query->where([
                    ["booked_at", ">=", $this->booked_at->subMinutes($this->restaurant->turnaround_time)],
                    ["booked_at", "<", $this->finish_at->addMinutes($this->restaurant->turnaround_time)],
                ]);
                $query->orWhere([
                    ["finish_at", ">", $this->booked_at->subMinutes($this->restaurant->turnaround_time)],
                    ["finish_at", "<=", $this->finish_at->addMinutes($this->restaurant->turnaround_time)],
                ]);
                $query->orWhere([
                    ["booked_at", "<=", $this->booked_at->subMinutes($this->restaurant->turnaround_time)],
                    ["finish_at", ">=", $this->finish_at->addMinutes($this->restaurant->turnaround_time)],
                ]);
            })->where("table_id", $this->table_id)
                ->whereIn("status", ["pending", "confirmed"])
                ->where("id", "!=", $this->id)
                ->count();

            if($tableBookings > 0){
                return false;
            }

            return true;
        }

        $currentBookingsLimit = $this->restaurant->bookings()->where([
            ["booked_at", ">", $this->booked_at->clone()->subMinutes($this->restaurant->booking_timeframe["minutes"])],
            ["booked_at", "<", $this->booked_at->clone()->addMinutes($this->restaurant->booking_timeframe["minutes"])],
        ])
            ->whereIn("status", ["pending", "confirmed"])
            ->count();

        if($currentBookingsLimit < $this->restaurant->booking_timeframe["tables"]){
            return $this->assignTable($group);
        }

        return false;
    }

    public function columns()
    {
        $length = $this->booked_at->diffInMinutes($this->finish_at);

        return $length/$this->restaurant->interval;
    }
}
