<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings - {{ $restaurant->name }}
            </h2>
            @livewire("restaurant.status", compact("restaurant"))
        </div>
    </x-slot>

    <div class="container mx-auto bg-white py-5 px-3" x-data="{ tab: 'details' }">
        <div class="space-y-3">
            @empty($restaurant->email_verified_at)
                @if(session()->has('verify_email.success'))
                    <x-alert class="bg-green-100 border-green-500 text-green-900">
                        <p class="font-bold">Email Address Verification Email Sent</p>
                        <p>
                            We have sent a verification link to your registered email address. Please check your inbox.
                        </p>
                    </x-alert>
                @else
                    <x-alert class="bg-orange-100 border-orange-500 text-orange-900">
                        <p class="font-bold">Unverified Email Address</p>
                        <p>
                            You have not yet verified your restaurant's email address. Please verify your email address
                            using the link that was emailed to you.
                            <a class="text-orange-600 hover:text-orange-300 underline"
                               href="{{ route('restaurant.verify_email.resend', [$restaurant->id]) }}">
                                Resend Verification Link
                            </a>
                        </p>
                    </x-alert>
                @endif
            @endempty

            @livewire('restaurant.status.offline', compact('restaurant'))

            @if(!$restaurant->hasDefaultPaymentMethod())
                <x-alert class="bg-orange-100 border-orange-500 text-orange-900">
                    <p class="font-bold">No Payment Method</p>
                    <p>
                        You dont have a payment method linked to your account. Please add a payment method in the
                        "Billing Settings" tab. You will not be able to go live until a payment method has been added.
                    </p>
                </x-alert>
            @endif
        </div>

        <div class="flex h-16 space-x-8 mb-5">
            <x-tab key="tab" value="details">Restaurant Details</x-tab>
            <x-tab key="tab" value="booking">Booking Settings</x-tab>
            <x-tab key="tab" value="billing">Billing Settings</x-tab>
        </div>

        <div x-cloak x-show="tab === 'details'">
            @include("restaurant.manage.details")
        </div>
        <div x-cloak x-show="tab === 'booking'">
            @include("restaurant.manage.booking_settings")
        </div>
        <div x-cloak x-show="tab === 'billing'">
            @include("restaurant.manage.billing_settings")
        </div>
    </div>
</x-app-layout>
