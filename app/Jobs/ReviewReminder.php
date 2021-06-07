<?php

namespace App\Jobs;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ReviewReminder implements ShouldQueue
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
        // get all bookings that finished over 2 hours ago
        $bookings = Booking::where("finish_at", "<", Carbon::now()->subHours(2))
            ->whereNull("review_reminder_at")
            ->whereIn("status", ["confirmed", "seated"])
            ->whereNotNull("booked_by")
            ->get();

        foreach($bookings as $booking){
            if(!empty($booking->booker->password)){
                // send an email asking to review
                Mail::to($booking->email)->queue(new \App\Mail\Booking\ReviewReminder($booking));

                $booking->review_reminder_at = Carbon::now();

                $booking->save();
            }
        }
    }
}
