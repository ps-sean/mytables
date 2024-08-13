<?php

namespace App\Livewire\Restaurant;

use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Book extends Component
{
    public Restaurant $restaurant;
    public $dates;
    public $selectedDate;
    public $times;
    public $services;
    public $booking;
    public $restaurant_sections;
    public $setup_intent;
    public $user;
    public $preAuthDate;
    public $section = "all";
    public $covers = 2;
    public $save_method = false;
    public $card_method = "add";
    public $max_covers;

    protected $rules = [
        "booking.covers" => ["required", "min:1"],
        "booking.name" => ["required"],
        "booking.restaurant_id" => ["required"],
        "booking.booked_at" => ["required"],
        "booking.booked_by" => [],
        "booking.email" => ["required", "email"],
        "booking.contact_number" => ['required', 'min:11', 'max:16', 'phone'],
        "booking.comments" => [],
        "save_method" => [],
        "card_method" => ["required", "in:add,default"],
    ];

    protected $listeners = ['captchaResponse' => 'processCaptcha'];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->selectedDate = Carbon::now();

        if($restaurant->sections->count()){
            $this->section = $restaurant->sections->first()->id;
        }

        $this->max_covers = $this->restaurant->max_booking_size($this->section);

        $this->rules["booking.covers"][] = "max:" . $this->max_covers;

        $this->restaurant_sections = $restaurant->sections()->whereHas("tables", function($query){
            return $query->where("bookable", 1);
        })->get();

        if(Auth::check() && Auth::user()->hasDefaultPaymentMethod()){
            $this->card_method = "default";
        }
    }

    public function render()
    {
        $this->dates = CarbonPeriod::create(Carbon::now(), Carbon::now()->addDays($this->restaurant->show_days))->toArray();
        $this->services = $this->restaurant->loadServices($this->selectedDate, $this->covers, $this->section);

        return view('livewire.restaurant.book');
    }

    public function selectDate($newDate)
    {
        $this->selectedDate = Carbon::parse($newDate);
    }

    public function updatedSection($value)
    {
        $this->max_covers = $this->restaurant->max_booking_size($value);

        $this->rules["booking.covers"][] = "max:" . $this->max_covers;
    }

    public function showBooking($time)
    {
        $this->booking = new Booking();
        $this->booking->restaurant_id = $this->restaurant->id;
        $this->booking->covers = $this->covers;
        $this->booking->booked_at = Carbon::parse($time);

        if(Auth::check()){
            $this->booking->booked_by = Auth::user()->id;
            $this->booking->name = Auth::user()->name;
            $this->booking->email = Auth::user()->email;
            $this->booking->contact_number = Auth::user()->contact_number;
        }

        // before passing back, check booking is valid
        if(!$this->booking->checkTime($this->section)){
            session()->flash("timeTaken");
            return;
        }

        $this->preAuthDate = $this->booking->booked_at->copy()->subDay();

        if($this->preAuthDate->format("Y-m-d H:i:s") < Carbon::now()->setTimezone("Europe/London")->format("Y-m-d H:i:s")){
            // this date has already passed. Get the next time the job will run
            $this->preAuthDate = Carbon::now()->setTimezone("Europe/London");

            $this->preAuthDate->minutes(ceil(($this->preAuthDate->minute+1)/5)*5);
        }

        $this->dispatch('initiate-booking')->self();
    }

    public function hideBooking()
    {
        $this->booking = null;
    }

    public function adjustCovers($adjustment)
    {
        if(1 <= ($this->covers + $adjustment)){
            $this->covers += $adjustment;
        }
    }

    public function setupCard()
    {
        $this->validate();

        if(empty($this->user)){
            if(Auth::check()){
                $user = Auth::user();
            } else {
                // check for existing customers with this email
                $user = User::where("email", $this->booking->email)->first();
            }

            if($user){
                $user->createOrGetStripeCustomer();

                if(empty($user->password)){
                    $user->name = $this->booking->name;
                }
            } else {
                $user = new User();

                $user->name = $this->booking->name;
                $user->email = $this->booking->email;

                $user->save();

                $user->createAsStripeCustomer();
            }

            $this->user = $user;
        }

        $this->setup_intent = $this->user->createSetupIntent()->client_secret;
    }

    public function processCaptcha($token)
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret=' . config('services.recaptcha.secret') . '&response=' . $token);
        $captcha = $response->json()['score'];

        if ( $captcha >= config('services.recaptcha.minimum_score') ) {
            $this->book();
        } else {
            $this->addError("captcha-error", "Something went wrong, please try again.");
        }
    }

    public function book($payment_method = null)
    {
        $this->validate();

        if($this->restaurant->no_show_fee > 0 && $this->card_method === "add" && empty($payment_method)){
            return $this->addError("payment-error", "Something went wrong, no payment details were provided.");
        }

        if($this->card_method === "default"){
            if(!Auth::check()){
                return redirect()->route("login");
            }

            if(!Auth::user()->hasDefaultPaymentMethod()){
                $this->card_method = "add";
                return $this->addError("payment-error", "You do not have a default payment method, please add one.");
            }

            $this->booking->payment_method = Auth::user()->defaultPaymentMethod()->id;
            $this->booking->booked_by = Auth::user()->id;
        } else {
            if(!empty($payment_method)){
                if(Auth::check() && $this->save_method){
                    $this->user->updateDefaultPaymentMethod($payment_method);
                } else {
                    $this->user->addPaymentMethod($payment_method);
                }

                $this->booking->booked_by = $this->user->id;
                $this->booking->payment_method = $payment_method;
            }
        }

        $this->booking->no_show_fee = $this->restaurant->no_show_fee;

        // check one last time for a table
        if($table = $this->booking->checkTime($this->section)){
            $this->booking->table_ids = [$table->id];

            $this->booking->save();

            if(Auth::check()){
                $user = Auth::user();

                if(empty($user->contact_number)){
                    $user->contact_number = $this->booking->contact_number;
                    $user->save();
                }
            }
        } else {
            session()->flash('timeTaken', 'This time has been taken');
        }
    }
}
