<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $restaurant->name }}
            </h2>
            @livewire("restaurant.status", compact("restaurant"))
        </div>
    </x-slot>

    <div class="container mx-auto bg-white py-5 px-3">
        @empty($restaurant->email_verified_at)
            <x-alert class="bg-orange-100 border-orange-500 text-orange-900">
                <p class="font-bold">Unverified Email Address</p>
                <p>You have not yet verified your restaurant's email address. Please verify your email address using the link that was emailed to you. <a class="text-orange-600 hover:text-orange-300 underline" wire:click="resendVerification" href="#">Resend Verification Link</a></p>
            </x-alert>
        @endempty

        @livewire('restaurant.status.complete', compact('restaurant'))

        @if($stripeAccount = $restaurant->stripeAccount())
            @if(!$stripeAccount->charges_enabled)
                <x-alert class="bg-red-100 border-red-500 text-red-900">
                    <p class="font-bold">Stripe Account Error</p>
                    @if(!$stripeAccount->details_submitted)
                        <p>You have not yet provided details for your stripe account. <a class="text-red-600 hover:text-red-300 underline" href="{{ $restaurant->linkAccountUrl() }}" target="_BLANK">Create your stripe account.</a></p>
                    @else
                        <p>There is an error with your stripe account. Please visit your <a class="text-red-600 hover:text-red-300 underline" href="{{ route("restaurant.stripe", $restaurant->id) }}" target="_BLANK">Stripe Dashboard</a> to resolve the issue.</p>
                    @endif
                </x-alert>
            @elseif(!$stripeAccount->payouts_enabled)
                <x-alert class="bg-orange-100 border-orange-500 text-orange-900">
                    <p class="font-bold">Payout Error</p>
                    <p>There is a problem with your account which is preventing us from paying money to you. Please visit your <a class="text-orange-600 hover:text-orange-300 underline" href="{{ route("restaurant.stripe", $restaurant->id) }}" target="_BLANK">Stripe Dashboard</a> to resolve the issue. Any money owed to you will be held until this issue is resolved.</p>
                </x-alert>
            @endif
        @endif

        <x-tabs :active="'Restaurant Details'">
            <x-tab id="restaurant_tab" name="Restaurant Details">
                @include("restaurant.manage.details")
            </x-tab>
            <x-tab id="booking_tab" name="Booking Settings">
                @include("restaurant.manage.booking_settings")
            </x-tab>
{{--            <x-tab id="order_tab" name="Order Settings">--}}
{{--                @livewire("restaurant.orders")--}}
{{--            </x-tab>--}}
        </x-tabs>
    </div>
</x-app-layout>
