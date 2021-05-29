<?php

namespace App\Jobs;

use App\Mail\UnreadMessage;
use App\Models\BookingMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UnreadMessageEmailer implements ShouldQueue
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
        $emails = (object)[
            "restaurants" => [],
            "users" => [],
        ];

        // get all unread messages created over 5 minutes ago
        $messages = BookingMessage::whereNull("read_at")->whereNull("emailed_at")->where("created_at", "<=", Carbon::now()->subMinutes(5))->get();

        foreach($messages as $message){
            if($message->created_by === $message->booking->booked_by){
                // the booker created the message
                // send an email to the restaurant
                if(!array_key_exists($message->booking->restaurant->email, $emails->restaurants)){
                    $emails->restaurants[$message->booking->restaurant->email] = (object)[
                        "details" => (object)[
                            "name" => strval($message->booking->restaurant),
                            "link" => "/restaurants/" . $message->booking->restaurant->id . "/bookings/",
                        ],
                        "bookings" => []
                    ];
                }

                $emails->restaurants[$message->booking->restaurant->email]->bookings[] = $message->booking;
            } else {
                // send the email to the person that booked
                if(!array_key_exists($message->booking->booker->email, $emails->users)){
                    $emails->users[$message->booking->booker->email] = (object)[
                        "details" => (object)[
                            "name" => strval($message->booking->booker),
                            "link" => "/bookings/",
                        ],
                        "bookings" => []
                    ];
                }

                $emails->users[$message->booking->booker->email]->bookings[] = $message->booking;
            }

            $message->emailed_at = Carbon::now();
            $message->save();
        }

        foreach($emails as $type => $email){
            foreach($email as $recipient => $values){
                Mail::to($recipient)->queue(new UnreadMessage($values));
            }
        }
    }
}
