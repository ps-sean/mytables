@component('mail::message')
Hi {{ $booking->name }},

We hope you enjoyed your booking with {{ $booking->restaurant }}.

We would be grateful if you could leave a review of your experience to help other diners.

@component('mail::button', ['url' => config('app.url') . "/bookings/" . $booking->id . "#review"])
    Leave a Review
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
