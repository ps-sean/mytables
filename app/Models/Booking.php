<?php

namespace App\Models;

use App\Events\BookingCreated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Booking extends Model
{
    use HasFactory, HasJsonRelationships;

    protected $connection = "mysql";

    protected $casts = [
        "table_ids" => "json",
        "booked_at" => "datetime:Y-m-d\TH:i",
        "finish_at" => "datetime:Y-m-d\TH:i",
        "review_reminder_at" => "datetime:Y-m-d\TH:i",
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

    public function tables()
    {
        return $this->belongsToJson(Table::class, "table_ids");
    }

    public function setBookedAtAttribute($value)
    {
        if (!$value instanceof Carbon) {
            $value = Carbon::parse(urldecode($value));
        }

        $this->attributes["booked_at"] = $value;

        if (!empty($this->covers)) {
            // set this based on the number of people at the table, from restaurant settings
            $this->attributes["finish_at"] = $value->clone()->addMinutes($this->restaurant->checkBookingRule($this->covers)->minutes);
        }
    }

    public function getTableNamesAttribute()
    {
        $names = [];

        foreach ($this->tables as $table) {
            $names[] = (string)$table;
        }

        return implode(", ", $names);
    }

    public function assignTable($section = "all")
    {
        $tablesUsed = [];

        // there is a free timeslot, now check how many tables are booked in between the start and finish time
        $seatedBookings = $this->restaurant->bookings()->where(function ($query) {
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
            ->whereIn("status", ["pending", "confirmed", "seated"])
            ->get();

        foreach ($seatedBookings as $booking) {
            $tablesUsed = array_merge($tablesUsed, $booking->table_ids);
        }

        $blockedTables = [];

        $blocks = $this->restaurant->tableBlocks()
            ->where("start_date", "<", $this->finish_at)
            ->where("end_date", ">", $this->booked_at)
            ->get();

        foreach ($blocks as $block) {
            $blockedTables = array_merge($blockedTables, $block->tables);
        }

        // get the smallest table available that isn't being used
        $tables = $this->restaurant->tables()
            ->where("bookable", 1)
            ->where("seats", ">=", $this->covers)
            ->whereNotIn("id", $tablesUsed)
            ->whereNotIn("id", $blockedTables)
            ->orderBy("seats");

        if ($section != "all") {
            $tables->where("restaurant_section_id", $section);
        }

        return $tables->first();
    }

    public function checkServices()
    {
        // check for exception services first
        $services = $this->restaurant->service_exceptions()->whereDate("service_date", $this->booked_at)->orderBy("start")->get();

        if ($services->count()) {
            $closed = $services->where("closed", 1);

            if ($closed->count()) {
                return collect([]);
            }

        } else {
            $services = $this->restaurant->services()->where("day", $this->booked_at->shortEnglishDayOfWeek)->orderBy("start")->get();
        }

        foreach ($services as $index => $service) {
            $start = $service->start->setDateFrom($this->booked_at);
            $finish = $service->last_booking->setDateFrom($this->booked_at);

            if ($start > $this->booked_at || $finish < $this->booked_at) {
                $services->forget($index);
            }
        }

        return $services;
    }

    public function checkTime($section = "all")
    {
        if ($this->exists) {
            // booking already exists, check if the new table and/or time is available
            // check for other bookings on these tables at the same time
            foreach ($this->table_ids as $tableID) {
                $tableBookings = $this->restaurant->bookings()->where(function ($query) {
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
                })->whereRaw("FIND_IN_SET(?, `table_ids`)", [$tableID])
                    ->whereIn("status", ["pending", "confirmed", "seated"])
                    ->where("id", "!=", $this->id)
                    ->count();

                if ($tableBookings > 0) {
                    return false;
                }
            }

            return true;
        }

        $currentBookingsLimit = $this->restaurant->bookings()->where([
            ["booked_at", ">", $this->booked_at->clone()->subMinutes($this->restaurant->booking_timeframe["minutes"])],
            ["booked_at", "<", $this->booked_at->clone()->addMinutes($this->restaurant->booking_timeframe["minutes"])],
        ])
            ->whereIn("status", ["pending", "confirmed", "seated"])
            ->sum("covers");

        if (($currentBookingsLimit + $this->covers) <= $this->restaurant->booking_timeframe["covers"]) {
            return $this->assignTable($section);
        }

        return false;
    }

    public function columns()
    {
        $length = $this->booked_at->diffInMinutes($this->finish_at);

        return floor($length / $this->restaurant->interval);
    }
}
