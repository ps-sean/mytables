<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\BookingMessageCreated;
use App\Events\BookingStatusUpdated;
use App\Listeners\AnnounceBooking;
use App\Listeners\AnnounceBookingStatusUpdate;
use App\Listeners\AutoConfirmBooking;
use App\Listeners\SendBookingMessageNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\RestaurantCreated;
use App\Listeners\SendRestaurantEmailVerification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BookingCreated::class => [
            AnnounceBooking::class,
            AutoConfirmBooking::class,
        ],
        RestaurantCreated::class => [
            SendRestaurantEmailVerification::class,
        ],
        BookingMessageCreated::class => [
            SendBookingMessageNotification::class,
        ],
        BookingStatusUpdated::class => [
            AnnounceBookingStatusUpdate::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
