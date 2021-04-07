@component('mail::message')

Name: {{ $name }}

<p style="white-space: pre;">{{ $text }}</p>

<p>This email was sent via myTables. Replies to this email are sent directly to the customer.</p>
@endcomponent
