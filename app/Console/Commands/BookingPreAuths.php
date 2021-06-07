<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BookingPreAuths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:preAuths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-Authorises payments in a customers bank to secure against a booking';

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
     *
     */
    public function handle()
    {
        \App\Jobs\BookingPreAuths::dispatch();
    }
}
