<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Contact Us
        </h2>
    </x-slot>

    <div class="container mx-auto bg-white p-5 space-y-10">
        <div class="space-y-3">
            <h2 class="text-xl font-bold">Problems with a booking/restaurant?</h2>
            <p>
                Please contact the restaurant to resolve any issues. Their contact details can be found on their profile.
            </p>
        </div>
        <div class="space-y-3">
            <h2 class="text-xl font-bold">Need to contact us?</h2>
            <p>
                You can reach us by email at
                <a class="text-red-800 hover:text-red-700 transition-all duration-150 ease-in-out"
                                                href="mailto:info@mytables.co.uk">info@mytables.co.uk</a>
            </p>
        </div>
    </div>
</x-app-layout>
