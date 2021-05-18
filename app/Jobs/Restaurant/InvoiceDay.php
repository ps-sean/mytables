<?php

namespace App\Jobs\Restaurant;

use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\StripeClient;

class InvoiceDay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $restaurant;

    /**
     * Create a new job instance.
     *
     * @param Restaurant $restaurant
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = Carbon::now()->subDay()->startOfDay();
        $end = $start->copy()->endOfDay();

        // check if restaurant was live at any point
        if(!$this->restaurant->checkHistory("status->text", "live", $start, $end)){
            // add an empty invoice item for the day
            if(empty($this->restaurant->stripe_id)){
                $this->restaurant->linkAccount();
                $this->restaurant->refresh();
            }

            $this->restaurant->addInvoiceItem("Offline on " . $start->toFormattedDateString(), 0);

            return;
        }

        $tables = $this->restaurant->tables()
            ->withTrashed()
            ->where("created_at", "<=", $end)
            ->where(function($query) use ($start){
                $query->whereNull("deleted_at");
                $query->orWhere("deleted_at", ">=", $start);
            })
            ->get();

        foreach($tables as $index => $table){
            // check if table was bookable by getting the history at the start of the day
            if(!$table->checkHistory("bookable", 1, $start, $end)){

                // table was not bookable
                $tables->forget($index);
            }
        }

        // get the daily rate for this restaurant
        $dailyRate = $this->restaurant->calculateDailyRate();

        $description = $tables->count() . " tables @ £" . $dailyRate . " per day on " . $start->toFormattedDateString();
        $amount = $tables->count() * $dailyRate * 100;

        $this->restaurant->addInvoiceItem($description, $amount);

        try{
            // check if today is the billing date and bill
            if(date('d') == $this->restaurant->billing_date){
                // invoice for all items
                $this->restaurant->payday();
            }
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }
}
