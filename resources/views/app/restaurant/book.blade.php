<x-frame-app-layout>
    @if($booking->checkTime($group))
        <form method="POST">
            <div class="bg-white">
                <div class="relative w-full h-64 bg-cover bg-center" style="background-image: url({{ $booking->restaurant->image }});">
                    @if(!empty($booking->restaurant->image_location) && !empty($booking->restaurant->logo_location))
                        <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $booking->restaurant->logo }}"/>
                    @endif
                </div>

                <div class="p-5 space-y-2">
                    <div class="flex">
                        <div class="px-2">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-lg">{{ $booking->restaurant->name }}</h5>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="px-2">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-lg">{{ $booking->restaurant->address }}</h5>
                        </div>
                    </div>

                    <div class="flex pt-3">
                        <div class="px-2">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-lg">{{ $booking->booked_at->toDayDateTimeString() }}</h5>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="px-2">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-lg">{{ $booking->finish_at->toDayDateTimeString() }}</h5>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="px-2">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-lg">{{ $booking->covers }} guests</h5>
                        </div>
                    </div>

                    @if(!empty($booking->id))
                        <div class="text-center">
                            <p>Thank you for booking your table with myTables.</p>
                            @if($booking->restaurant->table_confirmation === "automatic")
                                <p>Your booking has now been confirmed. You will soon receive an email confirming your booking details.</p>
                            @else
                                <p>Your booking request has been sent to {{ $booking->restaurant }} and you will receive an email when they respond.</p>
                            @endif
                        </div>
                    @else
                        <div class="flex">
                            <div class="px-2">
                                <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="w-full">
                                <x-jet-input type="text" name="booking[name]" class="w-full" placeholder="Name" required />
                                <x-jet-input type="email" name="booking[email]" class="w-full" placeholder="Email" required />
                            </div>
                        </div>

                        <div class="flex">
                            <div class="px-2">
                                <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="w-full">
                                <x-jet-input type="tel" name="booking[contact_number]" class="w-full" placeholder="Contact Number" required />
                            </div>
                        </div>

                        <div class="flex">
                            <div class="px-2">
                                <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="w-full">
                                <x-jet-input textarea name="booking[comments]" class="w-full" placeholder="Additional Comments"/>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if(empty($booking->id))
                <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-2">
                    <a href="{{ url()->previous() }}">
                        <x-button type="button" class="justify-center border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                            Cancel
                        </x-button>
                    </a>
                    <x-button id="submit-booking" type="submit" class="justify-center bg-green-500 hover:bg-green-600">
                        Book
                    </x-button>
                </div>
            @endif
        </form>
    @else
        <div class="p-4 text-center">
            <p>This time has now been taken, please try another time.</p>
            <p>
                <a href="/app/restaurant/{{ $restaurant->id }}" class="text-red-800 hover:text-red-700">
                    Go Back
                </a>
            </p>
        </div>
    @endif

    @push("scripts")
        <script>
            let bookingbtn = document.getElementById("submit-booking")
            let form = document.getElementsByTagName("form")[0]
            let inputs = document.getElementsByTagName("input")

            form.addEventListener("submit", () => {
                bookingbtn.disabled = true
            })

            for(let i = 0; i < inputs.length; i++){
                inputs[i].addEventListener("invalid", () => {
                    bookingbtn.disabled = false
                })
            }
        </script>
    @endpush
</x-frame-app-layout>
