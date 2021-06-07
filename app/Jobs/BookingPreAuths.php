<?php

namespace App\Jobs;

use App\Events\BookingStatusUpdated;
use App\Models\Booking;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;

class BookingPreAuths implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bookings = Booking::where("booked_at", "<=", Carbon::now()->setTimezone("Europe/London")->addDay())
            ->where("booked_at", ">=", Carbon::now()->setTimezone("Europe/London"))
            ->whereNotNull("payment_method")
            ->whereNotNull("booked_by")
            ->whereNotNull("no_show_fee")
            ->whereNull("payment_intent")
            ->where("status", "confirmed")
            ->where("no_show_fee", ">", 0)
            ->get();

        foreach($bookings as $booking){
            try{
                $intent = PaymentIntent::create([
                    'amount' => $booking->no_show_fee * 100,
                    'currency' => 'gbp',
                    'payment_method_types' => ['card'],
                    'capture_method' => 'manual',
                    'payment_method' => $booking->payment_method,
                    'customer' => $booking->booker->stripe_id,
                    'confirm' => true
                ]);

                $booking->payment_intent = $intent->id;
                $booking->save();
            } catch (\Exception $e) {
                // Something went wrong, cancel the booking
                $booking->reject_reason = "Booking was rejected due to pre-authorisation failure on card.";
                $booking->status = "rejected";
                $booking->save();

                BookingStatusUpdated::dispatch($booking);
            }
        }
    }
}
