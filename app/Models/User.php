<?php

namespace App\Models;

use App\Events\UserCreated;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function __toString()
    {
        return $this->name;
    }

    public function restaurants()
    {
        return $this->hasManyThrough(Restaurant::class, RestaurantStaff::class, "user_id", "id", "id", "restaurant_id");
    }

    public function futureBookings()
    {
        return $this->bookings()->where("booked_at", ">=", Carbon::now()->subHours(2))->orderBy("booked_at");
    }

    public function pastBookings()
    {
        return $this->bookings()->where("booked_at", "<", Carbon::now()->subHours(2))->orderByDesc("booked_at");
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, "booked_by");
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getInitialsAttribute()
    {
        $nameParts = explode(" ", $this->name);

        $initials = "";

        foreach($nameParts as $part){
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials;
    }

    public function getAdminAttribute()
    {
        return $this->belongsToTeam(Team::find(1));
    }

    public function getRestaurantBookingsPendingAttribute()
    {
        $bookings = 0;

        foreach($this->restaurants as $restaurant){
            $bookings += $restaurant->bookings()->whereDate("booked_at", ">=", Carbon::now())->where("status", "pending")->count();
        }

        return $bookings;
    }

    public function topRestaurants()
    {
        return Restaurant::query()
            ->where('status', 'live')
            ->whereHas('bookings', function($q){
                $q->where('booked_by', $this->getKey())
                    ->whereDate("booked_at", ">=", Carbon::now()->subYear());
            })
            ->withCount(['bookings' => function($q){
                $q->where('booked_by', $this->getKey())
                    ->whereDate("booked_at", ">=", Carbon::now()->subYear());
            }])->get();
    }
}
