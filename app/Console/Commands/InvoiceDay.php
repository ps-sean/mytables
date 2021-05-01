<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InvoiceDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds an invoice item to the restaurants customer account for the previous day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // dont start charging until 19th May. Job starts on 20th as we calculate the previous day
        if(Carbon::parse("2021-05-20")->isPast()){
            foreach(Restaurant::all() as $restaurant){
                \App\Jobs\Restaurant\InvoiceDay::dispatch($restaurant);
                $this->info("Job added for " . $restaurant->name);
            }
        }
    }
}
