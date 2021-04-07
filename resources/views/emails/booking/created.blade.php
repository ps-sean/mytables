@component('mail::message')
{{ $booking->name }} booked a table at {{ $booking->restaurant }}.

Date: {{ $booking->booked_at->toDayDateTimeString() }}
<br>
Guests: {{ $booking->covers }}
<br>
Phone: {{ $booking->contact_number }}
<br>
Email: {{ $booking->email }}
<br>
@if(!empty($booking->comments))
Comments: {{ $booking->comments }}
@endif

@component('mail::button', ['url' => route("restaurant.bookings", $booking->restaurant) . "?date=" . $booking->booked_at->format('Y-m-d') . "&search=" . urlencode($booking->name)])
View Booking
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
