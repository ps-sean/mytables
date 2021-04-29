<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            About Us
        </h2>
    </x-slot>

    <div class="container mx-auto bg-white p-5 space-y-10">
        <div class="space-y-3">
            <h2 class="text-xl font-bold">Who are we?</h2>
            <p>
                myTables is a table booking service created by
                <a class="text-red-800 hover:text-red-700 transition-all duration-150 ease-in-out"
                   href="https://str94.co.uk">STR94</a>, who provide websites and web applications for small businesses.
            </p>
        </div>
        <div class="space-y-3">
            <h2 class="text-xl font-bold">What do we do?</h2>
            <p>
                We connect customers to restaurants, allowing them to see nearby restaurants and book tables at a time
                convenient to them. We give restaurants more control over their bookings, allowing them see clearly what
                bookings they have and create bookings for customers, completely replacing any existing reservation
                diaries/systems. We allow restaurants to communicate with their customers to ensure the customer has the
                best experience possible during their visit.
            </p>
        </div>
        <div class="space-y-3">
            <h2 class="text-xl font-bold">Why do we do it?</h2>
            <p>
                We want to connect customers to their local restaurants and connect restaurants to their local
                communities. We love good food, a cold pint; and the atmosphere in our local pubs and restaurants.
                We want everyone to be able to experience it!
            </p>
        </div>
        <div class="space-y-3">
            <h2 class="text-xl font-bold">What do I pay?</h2>
            <p>
                As a customer, nothing! myTables is completely free! We just want to connect you to the restaurants we
                love.
            </p>
            <p>
                As a restaurant, we try to keep our prices as low as possible and offer a flat/scalable pricing
                structure that doesn't punish you for taking as many bookings as possible, in fact, we encourage it!
                Our pricing structure is designed to support all size restaurants from those with only a couple of
                tables, to those with hundreds. Our full pricing structure can be found on our
                <a class="text-red-800 hover:text-red-700 transition-all duration-150 ease-in-out"
                   href="{{ route("restaurant-sign-up") }}">restaurant sign up</a> page.
            </p>
        </div>
    </div>
</x-app-layout>
