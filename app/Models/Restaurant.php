<?php

namespace App\Models;

use App\Events\RestaurantCreated;
use App\Mail\Restaurant\EmailVerification;
use App\Traits\History;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Spatie\Activitylog\Traits\LogsActivity;
use Stripe\Customer;
use Stripe\StripeClient;

class Restaurant extends Model
{
    use HasFactory, Billable, History;

    protected $fillable = [
        "name",
        "email",
        "phone",
        "address_line_1",
        "vicinity",
        "country",
        "postal_code",
        "lat",
        "lng",
    ];

    protected $dispatchesEvents = [
        'created' => RestaurantCreated::class,
    ];

    protected $dates = [
        "email_verified_at"
    ];

    protected $casts = [
        'open_hours' => 'array'
    ];

    public function __toString()
    {
        return $this->name;
    }

    // Relationships
    public function staff()
    {
        return $this->belongsToMany(User::class, 'restaurant_staff');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function service_exceptions()
    {
        return $this->hasMany(ServiceException::class)->whereDate("service_date", ">=", Carbon::now());
    }

    public function open_hours_exceptions()
    {
        return $this->hasMany(OpenHoursException::class)->whereDate("open_date", ">=", Carbon::now());
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function booking_rules()
    {
        return $this->hasMany(BookingRule::class);
    }

    public function table_groups()
    {
        return $this->hasMany(TableGroup::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Booking::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function tableBlocks()
    {
        return $this->hasMany(TableBlock::class)
            ->where("end_date", ">", Carbon::now());
    }

    // Accessors & Mutators
    public function getImageAttribute()
    {
        if(empty($this->image_location)){
            return $this->logo;
        }

        return Storage::url($this->image_location);
    }

    // Accessors & Mutators
    public function getLogoAttribute()
    {
        if(empty($this->logo_location)){
            return asset("/img/placeholder.jpg");
        }

        return Storage::url($this->logo_location);
    }

    public function getAddressAttribute()
    {
        $parts = ["address_line_1", "vicinity", "postal_code"];
        $address = [];

        foreach($parts as $part){
            if(!empty($this->$part)){
                $address[] = $this->$part;
            }
        }

        return implode(", ", $address);
    }

    public function getRateAttribute()
    {
        return 5;
    }

    public function getStatusAttribute($value)
    {
        $status = (object)[
            "icon" => $this->statusIcon($value),
            "text" => ucwords($value),
            "color" => ""
        ];

        switch(strtolower($value)){
            case "live":
                $status->color = "text-green-300";

                break;

            case "offline":
            case "rejected":
                $status->color = "text-red-600";

                break;

            default:
                $status->color = "text-yellow-300";

                break;
        }

        return $status;
    }

    public function getBookingTimeframeAttribute($value)
    {
        $parts = explode(":", $value);

        return [
            "tables" => $parts[0],
            "minutes" => $parts[1]
        ];
    }

    public function setBookingTimeframeAttribute($value)
    {
        $this->attributes['booking_timeframe'] = $value['tables'] . ":" . $value['minutes'];
    }

    public function getMonthlyPaymentAttribute()
    {
        return $this->tables()->where("bookable", 1)->count() * $this->rate;
    }

    public function getNextBillingDateAttribute()
    {
        $date = Carbon::parse(date("Y") . "-" . date("m") . "-" . $this->billing_date);

        while($date->isPast()){
            $date->addMonth();
        }

        return $date;
    }

    public function getNextPaymentAmountAttribute()
    {
        // get all outstanding invoice items
        $amount = $this->asStripeCustomer()->balance/100;

        $amount += $this->invoiceItems->sum("amount")/100;

        $amount += Carbon::now()->startOfDay()->diffInDays($this->next_billing_date) * $this->tables()->where("bookable", 1)->count() * $this->calculateDailyRate();

        return number_format($amount, 2);
    }

    // functions
    public function max_booking_size($group = "all")
    {
        if($group === "all"){
            $largestTable = $this->tables()
                ->where("bookable", 1)
                ->orderBy("seats", "DESC")
                ->first();
        } else {
            $largestTable = $this->tables()
                ->where("table_group_id", $group)
                ->where("bookable", 1)
                ->orderBy("seats", "DESC")
                ->first();
        }

        if(!$largestTable){
            return 0;
        }

        return $largestTable->seats;
    }

    public function statusIcon($status)
    {
        $icons = [
            "live" => '<svg class="stroke-current h-full animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <circle cx="12" cy="12" r="6" fill="currentColor" />
                            </svg>',
            "offline" => '<svg class="stroke-current h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>',
            "pending" => '<svg class="stroke-current h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>',
            "rejected" => '<svg class="stroke-current h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>',
        ];

        return $icons[$status];
    }

    public function myRestaurant()
    {
        return $this->staff()->where("user_id", Auth::user()->id)->first();
    }

    public function linkAccount()
    {

        return $this->createAsStripeCustomer([
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "address" => [
                "line1" => $this->address_line_1,
                "city" => $this->vicinity,
                "postal_code" => $this->postal_code,
            ]
        ]);
    }

    public function servicesByDate(Carbon $date)
    {
        // check for exception services first
        $services = $this->service_exceptions()->whereDate("service_date", $date)->orderBy("start")->get();

        if($services->count()){
            $closed = $services->where("closed", 1);

            if($closed->count()){
                return collect([]);
            }

            return $services;
        } else {
            return $this->services()->where("day", $date->shortEnglishDayOfWeek)->orderBy("start")->get();
        }
    }

    public function servicePeriod(Carbon $date)
    {
        $times = collect([]);

        $services = $this->servicesByDate($date);

        if($services->count()){
            $dayStart = Carbon::parse($date->format("Y-m-d") . " " . $services->first()->start->format("H:i:s"));
            $dayFinish = Carbon::parse($date->format("Y-m-d") . " " . $services->first()->finish->format("H:i:s"));

            if($dayFinish->lessThanOrEqualTo($dayStart)){
                $dayFinish->addDay();
            }

            foreach($services as $service){
                $serviceStart = Carbon::parse($date->format("Y-m-d") . " " . $service->start->format("H:i:s"));
                $serviceFinish = Carbon::parse($date->format("Y-m-d") . " " . $service->finish->format("H:i:s"));

                if($serviceFinish->lessThanOrEqualTo($serviceStart)){
                    $serviceFinish->addDay();
                }

                if($serviceFinish->greaterThan($dayFinish)){
                    $dayFinish = $serviceFinish;
                }
            }
        }

        if($b = $this->bookings()->whereDate("booked_at", $date)->orderBy("booked_at")->first()){
            if(empty($dayStart) || $b->booked_at->lessThan($dayStart)){
                $dayStart = $b->booked_at;
            }
        }

        if($b = $this->bookings()->whereDate("booked_at", $date)->orderBy("finish_at", "DESC")->first()){
            if(empty($dayFinish) || $b->finish_at->greaterThan($dayFinish)){
                $dayFinish = $b->finish_at;
            }
        }

        if(!empty($dayStart) && !empty($dayFinish)){
            $times = CarbonPeriod::create($dayStart, CarbonInterval::minutes($this->interval), $dayFinish->subMinutes($this->interval));
        }

        return $times;
    }

    public function allTimes(Carbon $date, $covers, $group = "all")
    {
        $serviceTimes = [];

        // get service for day of week
        $services = $this->servicesByDate($date);

        if($services->count()){
            foreach($services as $service){
                $serviceStart = Carbon::parse($date->format("Y-m-d") . " " . $service->start->format("H:i:s"));
                $serviceFinish = Carbon::parse($date->format("Y-m-d") . " " . $service->last_booking->format("H:i:s"));

                if($serviceFinish->lessThanOrEqualTo($serviceStart)){
                    $serviceFinish->addDay();
                }

                $currentTime = $serviceStart->clone();

                while($currentTime->lessThanOrEqualTo($serviceFinish)){
                    $booking = $this->bookings()->make([
                        "restaurant_id" => $this->id,
                        "covers" => $covers,
                        "booked_at" => $currentTime,
                    ]);

                    if($booking->checkTime($group)){
                        $serviceTimes[] = $currentTime->clone();
                    }

                    $currentTime->addMinutes($this->interval);
                }
            }
        }

        return collect($serviceTimes)->unique();
    }

    public function loadServices(Carbon $date, $covers, $group = "all")
    {
        $times = $this->allTimes($date, $covers, $group);

        $available = [];

        foreach($times as $time){
            if($time->format("Y-m-d H:i:s") > Carbon::now()->setTimezone("Europe/London")->format("Y-m-d H:i:s")){
                // get available services
                // check services from the night before to make sure we're not still serving
                // check exception days first
                $services = $this->service_exceptions()->whereDate("service_date", $time->clone()->subDay())
                    ->where("finish", ">=", $time->format("H:i"))
                    ->whereRaw("finish<=start")
                    ->get();

                if($services->count() < 1){
                    $services = $this->services()->where([
                        ["day", $time->clone()->subDay()->shortEnglishDayOfWeek],
                        ["finish", ">=", $time->format("H:i")]
                    ])
                        ->whereRaw("finish<=start")
                        ->get();
                }

                // check for todays services starting with exceptions
                $todays = $this->service_exceptions()->whereDate("service_date", $time)
                    ->where("start", "<=", $time->format("H:i"))
                    ->where(function($query) use ($time){
                        $query->where("finish", ">=", $time->format("H:i"));
                        $query->orWhereRaw("finish<=start");
                    })->get();

                if($todays->count() < 1){
                    $todays = $this->services()->where([
                        ["day", $time->shortEnglishDayOfWeek],
                        ["start", "<=", $time->format("H:i")],
                    ])->where(function($query) use ($time){
                        $query->where("finish", ">=", $time->format("H:i"));
                        $query->orWhereRaw("finish<=start");
                    })->get();
                }

                $services = $services->merge($todays);

                $available[] = (object)[
                    "services" => $services,
                    "time" => $time,
                ];
            }
        }

        return collect($available);
    }

    public function sendEmailVerification()
    {
        Mail::to($this->email)->queue(new EmailVerification($this));
    }

    public function checkBookingRule($covers)
    {
        $rule = $this->booking_rules()->where("max_covers", ">=", $covers)->first();

        if(!$rule){
            $rule = $this->booking_rules()->orderBy("max_covers", "DESC")->first();

            if(!$rule){
                $rule = BookingRule::make([
                    "max_covers" => $covers,
                    "minutes" => 120,
                ]);
            }
        }

        return $rule;
    }

    public function checkOpenHoursExceptions($day)
    {
        $date = Carbon::now();

        if($day !== $date->shortEnglishDayOfWeek){
            $date = Carbon::now()->next($day);
        }

        // check for exceptions
        return $this->open_hours_exceptions()->whereDate("open_date", $date)->first();
    }

    public function averageReview($option = "overall")
    {
        $avg = $this->reviews()->avg($option);

        if(!$avg){
            return null;
        }

        return number_format($avg, "2");
    }

    public function wasLive($date)
    {
        $status = [];

        // get the last activity before the date
        $lastActivity = $this->activities()->where("created_at", "<", $date->startOfDay())->orderBy("created_at", "DESC")->first();

        if($lastActivity){
            $status[] = strtoupper($lastActivity->properties["attributes"]["status"]["text"]);
        }

        // get all activity during the day
        foreach($this->activities()->whereDate("created_at", $date)->get() as $activity){
            $status[] = strtoupper($activity->properties["attributes"]["status"]["text"]);
        }

        return in_array("LIVE", $status);
    }

    public function calculateDailyRate()
    {
        if(empty($this->stripe_id)){
            $this->linkAccount();
            $this->refresh();
        }

        $stripeClient = new StripeClient(config("services.stripe.secret"));

        $invoices = $stripeClient->invoices->all([
            "customer" => $this->stripe_id,
            "limit" => 1,
        ]);

        // get the end date of the last invoice, make sure to set to start of day
        if($invoice = $invoices->first()){
            $startDate = Carbon::createFromTimestamp($invoice->period_end)->startOfDay();
        } else {
            $startDate = $this->created_at;

            if($this->created_at < Carbon::parse("2021-05-19")){
                $startDate = Carbon::parse("2021-05-19");
            }
        }

        // get the natural end date
        $naturalEnd = $startDate->copy()->addMonth();
        $monthsAdded = 1;

        while(!$naturalEnd->isToday() && $naturalEnd->isPast()){
            $naturalEnd->addMonth();
            $monthsAdded++;
        }

        // how many days between?
        $days = $startDate->diffInDays($naturalEnd);

        return ($this->rate*$monthsAdded)/$days;
    }

    public function addInvoiceItem($description, $amount)
    {
        return $this->invoiceItems()->create([
            'description' => $description,
            'amount' => $amount
        ]);
    }

    public function payday()
    {
        $roundedAmount = 0;

        $items = $this->invoiceItems;

        if($items->count()){
            foreach($items as $item){
                $this->tab($item->description, round($item->amount));
                $roundedAmount += round($item->amount);
                $item->delete();
            }

            $roundingAdjustment = round($items->sum("amount") - $roundedAmount);

            if($roundingAdjustment != 0) {
                $this->tab("Rounding Adjustment", $roundingAdjustment);
            }

            $this->invoice();
        }
    }
}
