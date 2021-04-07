@component('mail::message')
Hi {{ $recipient->name }},

You have unread message(s) regarding the bookings below:

@foreach($bookings as $booking)
<p>{{ $booking }} <a href="{{ config("app.url") . $recipient->link . $booking->id }}">View Messages</a></p>
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
