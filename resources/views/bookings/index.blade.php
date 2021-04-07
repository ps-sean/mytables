<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Bookings
        </h2>
    </x-slot>
    <div class="container mx-auto bg-white p-5">
        <x-tabs active="Future Bookings">
            <x-tab name="Future Bookings">
                @livewire("booking.index", ['bookings' => "futureBookings"])
            </x-tab>
            <x-tab name="Past Bookings">
                @livewire("booking.index", ['bookings' => "pastBookings"])
            </x-tab>
        </x-tabs>
    </div>
</x-app-layout>
