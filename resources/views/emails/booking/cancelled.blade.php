@component('mail::message')
Hi {{ $booking->restaurant }},

{{ $booking->name }} cancelled their booking <b>#{{ $booking->id }}</b> on {{ $booking->booked_at->toDayDateTimeString() }}.

@component('mail::button', ['url' => config('app.url') . "/restaurants/" . $booking->restaurant->id . "/bookings/" . $booking->id])
    View Booking
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
