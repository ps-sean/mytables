@component('mail::message')
Hi {{ $booking->name }},

Your booking <b>#{{ $booking->id }}</b> with {{ $booking->restaurant }} has been updated.

Status: <b>{{ $booking->status }}</b><br>
Name: {{ $booking->name }}<br>
Guests: {{ $booking->covers }}<br>
At: {{ $booking->booked_at->toDayDateTimeString() }}<br>
@if(!empty($booking->comments))<br>
Comments: {{ $booking->comments }}<br>
@endif

@if(!empty($booking->reject_reason))
Reason: {{ $booking->reject_reason }}
@endif

@if(!empty($booking->booked_by))
@component('mail::button', ['url' => config('app.url') . "/bookings/" . $booking->id])
    View Booking
@endcomponent
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
