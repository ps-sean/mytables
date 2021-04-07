@component('mail::message')
Hi {{ $booking->name }},

Your booking <b>#{{ $booking->id }}</b> with {{ $booking->restaurant }} on {{ $booking->booked_at->toDayDateTimeString() }} has been updated to <b>{{ $booking->status }}</b>.

@component('mail::button', ['url' => config('app.url') . "/bookings/" . $booking->id])
    View Booking
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
