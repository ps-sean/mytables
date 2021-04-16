@component('mail::message')
Hi {{ $restaurant }},

After reviewing your account we've changed your restaurant's status to <b>{{ $restaurant->status->text }}</b>.

@if(strtolower($restaurant->status->text) === "offline")
You can now get your restaurant live by clicking the banner in the restaurant manager.
@endif

@component('mail::button', ['url' => config('app.url') . "/restaurants/" . $restaurant->id . "/manage"])
    View Restaurant Manager
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
