<div wire:keydown.escape="hideBooking">
    <div class="p-5 space-y-5">
        <div class="flex overflow-auto rounded border mx-auto max-w-full" style="width: fit-content;">
            @foreach($dates as $date)
                <div wire:click="selectDate('{{ $date->format("Y-m-d") }}')" class="py-3 px-5 text-center cursor-pointer hover:bg-red-700 hover:text-white transition duration-500 ease-in-out {{ $date->isSameDay($selectedDate) ? 'bg-red-800 text-white shadow-inner' : '' }} {{ $loop->last ? '' : 'border-r' }}">
                    {{ $date->isoFormat("ddd") }}<br>
                    {{ $date->isoFormat("Do") }}<br>
                    {{ $date->isoFormat("MMM") }}
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center justify-center">
                <x-button wire:loading.attr="disabled" wire:target="adjustCovers" wire:click.prevent="adjustCovers(-1)" class="bg-red-800 hover:bg-red-700 rounded-r-none">&minus;</x-button>
                <p class="px-3">{{ $covers }} {{ \Illuminate\Support\Str::plural("guest", $covers) }}</p>
                <x-button wire:loading.attr="disabled" wire:target="adjustCovers" wire:click.prevent="adjustCovers(1)" class="bg-red-800 hover:bg-red-700 rounded-l-none">&plus;</x-button>
            </div>
            <div class="flex items-center justify-center">
                <label>Location:</label>
                <x-select wire:model="group">
                    <option value="all">All</option>
                    @foreach($restaurant->table_groups as $group)
                        <option value="{{ $group->id }}">{{ $group }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>

        <div wire:loading.block wire:target="selectDate" class="text-center">
            <img class="mx-auto h-16" src="{{ asset("img/loading.gif") }}"/>
        </div>

        @if($covers > $restaurant->max_booking_size)
            <div class="lg:wd-1/2 space-y-5">
                <p>{{ $restaurant }} can only take bookings of up to {{ $restaurant->max_booking_size }} guests online. Please contact the restaurant using the form below to book bigger tables and they will get back to you as soon as possible.</p>
                <div class="md:w-1/2 mx-auto">
                    @livewire("contact-form", ["subject" => "Booking Enquiry", "to" => $restaurant->email, "extras" => ["Date: " . $selectedDate->toDayDateTimeString(), "Guests: " . $covers]])
                </div>
            </div>
        @else
            <div wire:loading.remove wire:target="selectDate" class="grid md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @if($services->count())
                    @foreach($services as $serviceTime)
                        <a href="#" wire:click.prevent="showBooking('{{ $serviceTime->time }}')" class="shadow rounded p-5 text-center hover:bg-red-800 hover:text-white transition ease-in-out duration-500 cursor-pointer">
                            <h5 class="font-bold text-lg">{{ $serviceTime->time->format("H:ia") }}</h5>
                            @foreach($serviceTime->services as $service)
                                <p>
                                    @if(!empty($service->description))
                                        <svg class="h-4 inline text-gray-400 hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <title>{{ $service->description }}</title>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    {{ $service }}
                                </p>
                            @endforeach
                        </a>
                    @endforeach
                @else
                    <div class="col-span-full text-center">
                        <p>No tables free on {{ $selectedDate->toFormattedDateString() }} for {{ $covers }} guests.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($booking)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div wire:click="hideBooking" class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white">

                        <div class="w-full h-64 bg-cover bg-center" style="background-image: url({{ $booking->restaurant->image }});">

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
                                @auth
                                    <div class="flex">
                                        <div class="px-2">
                                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p>{{ $booking->name }}</p>
                                            <p class="text-gray-500">{{ $booking->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex">
                                        <div class="px-2">
                                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="w-full">
                                            <x-jet-input type="text" wire:model="booking.name" class="w-full" placeholder="Name" />
                                            @error("booking.name")<span class="text-red-600">{{ $message }}</span>@enderror
                                            <x-jet-input type="email" wire:model="booking.email" class="w-full" placeholder="Email" />
                                            @error("booking.email")<span class="text-red-600">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                @endauth

                                <div class="flex">
                                    <div class="px-2">
                                        <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div class="w-full">
                                        <x-jet-input type="tel" wire:model="booking.contact_number" class="w-full" placeholder="Contact Number" />
                                        @error("booking.contact_number")<span class="text-red-600">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="px-2">
                                        <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div class="w-full">
                                        <x-jet-input textarea wire:model="booking.comments" class="w-full" placeholder="Additional Comments"/>
                                        @error("booking.comments")<span class="text-red-600">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        @if(empty($booking->id) && !session()->has("timeTaken"))
                            <x-button wire:loading.attr="disabled" wire:click.prevent="book" type="button" class="justify-center bg-green-500 hover:bg-green-600">
                                Book
                            </x-button>
                        @endif
                        <x-button wire:click.prevent="hideBooking" type="button" class="justify-center border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                            {{ empty($booking->id) ? 'Cancel' : 'Close' }}
                        </x-button>
                        @if(session()->has("timeTaken"))
                            <span class="text-red-600">This time has now been taken, please try another time.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
