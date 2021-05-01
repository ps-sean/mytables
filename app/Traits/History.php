<?php


namespace App\Traits;


use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

trait History
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    public function checkHistory($attribute, $value, Carbon $start, Carbon $end)
    {
        // get the last history before the start of the day
        $firstActivity = $this->activities()
            ->where("created_at", "<", $start)
            ->orderBy("created_at", "DESC")
            ->first();

        if(!$firstActivity){
            // no activity before start of day, get the first during
            $firstActivity = $this->activities()
                ->where("created_at", "<", $end)
                ->first();
        }

        if(!$firstActivity){
            // still no activity, it shouldn't really have got here
            $firstActivity = $this->activities()->first();
        }

        if(!$firstActivity){
            // no activity was ever recorded, use current value
            return $this->checkAttribute($this, $attribute, $value);
        }

        if($this->checkAttribute($firstActivity->properties['attributes'], $attribute, $value)){
            // attribute matched value at the start of the day
            return true;
        }

        // attribute did not match value, check each history up until the end of the day
        $dayActivity = $this->activities()
            ->where("created_at", ">=", $start)
            ->where("created_at", "<=", $end)
            ->get();

        foreach($dayActivity as $activity){
            if($this->checkAttribute($activity->properties['attributes'], $attribute, $value)){
                // attribute matched value at some point in the day
                return true;
            }
        }

        return false;
    }

    private function checkAttribute($attributes, $attribute, $check)
    {
        $value = $attributes;

        foreach(explode("->", $attribute) as $a){
            if(is_array($value)){
                $value = (object)$value;
            }

            $value = $value->$a;
        }

        if(is_string($value)){
            return strtoupper($value) === strtoupper((string)$check);
        }

        return $value == $check;
    }
}
