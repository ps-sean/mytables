@component('mail::message')
<h1>Hi {{ $restaurant->name }}!</h1>

Please click the button below to verify your restaurants email address.

@component('mail::button', ['url' => $signedRoute])
Verify Email Address
@endcomponent

Please note: this link will only be valid for 15 minutes.

Regards,<br>
{{ config('app.name') }}

<hr>
If you’re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: {{ $signedRoute }}
@endcomponent
