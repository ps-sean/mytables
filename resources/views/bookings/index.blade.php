<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Bookings
        </h2>
    </x-slot>
    <div class="container mx-auto bg-white p-5" x-data="{ tab: 'future' }">
        <div class="flex h-16 space-x-8 mb-5">
            <x-tab key="tab" value="future">Future Bookings</x-tab>
            <x-tab key="tab" value="past">Past Bookings</x-tab>
        </div>

        <div x-cloak x-show="tab === 'future'">
            @livewire("booking.index", ['bookings' => "futureBookings"])
        </div>

        <div x-cloak x-show="tab === 'past'">
            @livewire("booking.index", ['bookings' => "pastBookings"])
        </div>
    </div>
</x-app-layout>
