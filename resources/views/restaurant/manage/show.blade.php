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

        @livewire('restaurant.status.offline', compact('restaurant'))

{{--        TODO: check if customer account setup--}}

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
