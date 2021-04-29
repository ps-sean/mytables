<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Restaurant;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\Charge;

class Bill implements ShouldQueue
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
        // TODO: split this part out into a command and run 1 job per restaurant, then if the job fails, only that restaurant is affected
        foreach(Restaurant::all() as $restaurant){
            // set start date to invoice start date, if none, set to restaurant created date
            $invoice = $restaurant->invoices()->orderBy("id", "DESC")->first();

            if($invoice){
                if(!empty($invoice->charged_at)){
                    $start = $invoice->end->startOfDay();

                    // the last invoice has been charged, create a new one
                    $invoice = $restaurant->invoices()->make();
                    $invoice->start = $start;
                } else {
                    $start = $invoice->start->startOfDay();
                }
            } else {
                $invoice = $restaurant->invoices()->make();
                $start = $restaurant->created_at->startOfDay();
                $invoice->start = $start;
            }

            // check billing date of restaurant, if none set, set one, if date after 28th, set to 28th
            if(empty($restaurant->billing_date)){
                $restaurant->billing_date = date("d");

                if($restaurant->billing_date > 28){
                    $restaurant->billing_date = 28;
                }

                $restaurant->save();
            }

            // get the natural end date for this bill, 1 month from the start
            $naturalEnd = $start->copy()->addMonth();

            while($naturalEnd->isPast()){
                $naturalEnd->addMonth();
            }

            // get the next occurrence of the billing date
            $end = Carbon::parse(date("Y-m-" . $restaurant->billing_date));

            while(!$end->isToday() && $end->isPast()){
                $end->addMonth();
            }

            $invoice->end = $end;

            $diff = $start->diffInDays($naturalEnd);
            $dailyRate = $restaurant->rate/$diff;

            // TODO check the history of the table to see if bookable has been changed

            // get the number of tables at the start of the month
            $tableCount = $restaurant->tables()->withTrashed()
                ->where("created_at", "<", $start)
                ->where("bookable", 1)
                ->where(function($query) use ($start){
                    $query->whereNull("deleted_at")
                        ->orWhere("deleted_at", ">=", $start);
                })
                ->count();

            $invoice->starting_tables = $tableCount;
            $invoice->expected = $tableCount*$restaurant->rate;

            $period = CarbonPeriod::create($start, $end);

            $notes = [];

            foreach($period as $day){
                if(!$day->isSameDay($end)){
                    foreach($restaurant->tablesCreatedOnDate($day) as $table){
                        $notes[] = $table . " created on " . $table->created_at->toDayDateTimeString();
                    }

                    foreach($restaurant->tablesDeletedOnDate($day) as $table){
                        $notes[] = $table . " deleted on " . $table->deleted_at->toDayDateTimeString();
                    }

                    if($restaurant->wasOnlineOnDate($day)){
                        // charge for live tables that day
                        $charge = $restaurant->tablesOnDate($day)*$dailyRate;

                        $invoice->actual += $charge;
                        $notes[] = "£" . number_format($charge, 2) . " charged for " . $day->toFormattedDateString();
                    } else {
                        $notes[] = $restaurant . " was offline all day on " . $day->toFormattedDateString();
                    }
                }
            }

            $invoice->notes = $notes;
            $invoice->actual = number_format($invoice->actual, 2);

            if($invoice->end->isPast()){
                // TODO: change to customer account
                $stripeAccount = $restaurant->stripeAccount();
                // charge for this invoice if greater than the minimum value
                if($stripeAccount && $stripeAccount->charges_enabled && $invoice->actual >= 0.30){
                    // TODO: we're going to have to make restaurants customers here
                    $charge = Charge::create([
                        "amount" => $invoice->actual*100,
                        "currency" => "gbp",
                    ]);

                    if($charge){
                        $invoice->charged_at = Carbon::now();
                    }
                }
            }
        }
    }
}
