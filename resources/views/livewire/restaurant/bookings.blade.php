<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $restaurant->name }}
            </h2>
        </div>
    </x-slot>
    <div class="bg-white p-5">
        <div class="container mx-auto md:flex justify-between space-y-3 mb-6">
            <x-jet-input class="w-full md:w-auto" wire:model="search" placeholder="Search"/>
            @if($view === "list")
                <div class="w-full md:w-auto">
                    Status:&nbsp;
                    <x-select wire:model="status">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="rejected">Rejected</option>
                    </x-select>
                </div>
            @else
                <x-jet-input class="w-full md:w-auto" wire:model="date" type="date"/>
            @endif
            <div class="w-full md:w-auto">
                View:&nbsp;
                <x-select wire:model="view">
                    <option value="grid">Grid</option>
                    <option value="list">List</option>
                </x-select>
            </div>
        </div>

        @switch($view)
            @case("grid")
                @if($restaurant->servicesByDate(\Carbon\Carbon::parse($date))->count() < 1 && $restaurant->bookings()->whereDate("booked_at", $date)->count() < 1)
                    <div wire:loading.class="opacity-25" class="flex justify-center text-center">
                        <p class="text-3xl">No services for this date.</p>
                    </div>
                @else
                    <div wire:loading.class="opacity-50" class="overflow-auto">
                        <table>
                            <thead>
                            <tr class="bg-red-800 text-white">
                                <th class="border" style="min-width: 200px;"></th>
                                @foreach($period as $time)
                                    @if($loop->index%4 === 0)
                                        <th class="border text-left px-3 py-2" colspan="4">{{  $time->format("h:ia") }}</th>
                                    @endif
                                @endforeach
                            </tr>
                            <tr>
                                <th class="p-0 h-0"></th>
                                @foreach($period as $time)
                                    <td class="p-0 h-0" style="min-width: 50px;"></td>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th class="text-left px-3 py-2 border bg-gray-600 text-white border-white" colspan="{{ $period->count() + 1 }}">Services</th>
                            </tr>
                            @if($services->count())
                                @foreach($services as $service)
                                    @php($serviceStart = \Carbon\Carbon::parse($date . " " . $service->start->format("H:i:s")))
                                    <tr>
                                        <th class="border px-3 py-2 {{ $loop->even ? '' : 'bgr-gray-100' }}">{{ $service }}</th>
                                        @php($cols = 0)
                                        @foreach($period as $time)
                                            @if($cols < 1)
                                                @if($time->equalTo($serviceStart))
                                                    @php($cols = $service->columns())
                                                    <td class="border bg-red-800 text-white text-center" colspan="{{ $cols }}">{{ \Carbon\Carbon::parse(date("Y-m-d " . $service->start->format("H:i:s")))->format("h:ia") }} - {{ \Carbon\Carbon::parse(date("Y-m-d " . $service->finish->format("H:i:s")))->format("h:ia") }}</td>
                                                @else
                                                    <td class="border bg-gray-300 border-white"></td>
                                                @endif
                                            @endif
                                            @php($cols--)
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border text-center px-3 py-2 bg-red-800 text-white" colspan="{{ $period->count() + 1 }}">No Services</td>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-left px-3 py-2 border bg-gray-600 text-white border-white" colspan="{{ $period->count() + 1 }}">Bookings</th>
                            </tr>
                            @foreach($tables as $table)
                                @php($bgGray = $loop->even)
                                <tr>
                                    <th class="border px-3 py-2 {{ $loop->even ? '' : 'bg-gray-100' }}">{{ $table }}</th>
                                    @php($cols = 0)
                                    @foreach($period as $time)
                                        @if($cols < 1)
                                            @if($booking = $table->bookings()->whereNotIn("status", ["cancelled", "rejected"])->whereBetween("booked_at", [$time, $time->copy()->addMinutes($restaurant->interval - 1)])->first())
                                                @php($cols = $booking->columns())
                                                <td class="border p-0" data-covers="{{ $booking->covers }}" colspan="{{ $cols }}">
                                                    <a class="h-full" href="{{ route("restaurant.booking", [$restaurant, $booking]) }}">
                                                        <div  class="relative w-full h-full px-3 py-2 hover:bg-red-700 hover:text-white transition-all duration-150 ease-in-out {{ (!empty($search) && preg_match("/{$search}/i", $booking->name)) ? 'bg-red-800 text-white' : ($bgGray ? 'bg-gray-100' : '') }}">
                                                            <div class="flex justify-between">
                                                                <h5 class="flex space-x-2">
                                                                    <span class="font-bold">{{ $booking->name }}</span>
                                                                    <span class="flex">
                                                                    <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                    {{ $booking->covers }}
                                                                </span>
                                                                </h5>
                                                                <p>{{ $booking->tableNumber }}</p>
                                                            </div>
                                                            <p>{{ $booking->booked_at->format("h:ia") }} - {{ $booking->finish_at->format("h:ia") }}</p>
                                                            @if(!empty($booking->comments))
                                                                @if(strlen($booking->comments) > 25)
                                                                    <p title="{{ $booking->comments }}">{{ substr($booking->comments, 0, 25) }}...</p>
                                                                @else
                                                                    <p>{{ $booking->comments }}</p>
                                                                @endif
                                                            @endif
                                                            @if($booking->status === "pending")
                                                                <div class="flex justify-center items-center absolute inset-0 bg-gray-300 bg-opacity-75 text-gray-700 hover:text-gray-500">
                                                                    <i>Pending</i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </td>
                                                @php($bgGray = !$bgGray)
                                            @else
                                                <td wire:click="createNewBooking('{{ $time }}', '{{ $table->id }}')" x-data="{ hover: false }" x-on:mouseenter="hover = true" x-on:mouseleave="hover = false" class="border border-white bg-gray-300 text-center cursor-pointer justify-center align-middle text-white hover:bg-red-800 transition-all ease-in-out duration-150" style="min-width: 50px;">
                                                    <x-icons.plus x-cloak x-show.transition.in="hover" class="h-8 mx-auto"/>
                                                </td>
                                            @endif
                                        @endif
                                        @php($cols--)
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @break
            @case("list")
                <div wire:loading.class="opacity-50" class="overflow-auto">
                    <table class="w-full">
                        <thead class="bg-gray-300 border-b border-gray-700">
                        <tr class="text-left">
                            <th class="p-3">Name</th>
                            <th class="p-3">Guests</th>
                            <th class="p-3">Table</th>
                            <th class="p-3">Booked At</th>
                            <th class="p-3">Finish At</th>
                            <th class="p-3">Comments</th>
                            <th class="p-3">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            <tr class="{{ $loop->even ? 'bg-gray-100' : '' }} border-b border-gray-500">
                                <th class="p-3 text-left">
                                    <a class="text-red-800 hover:text-red-500 underline transition-all duration-150 ease-in-out" href="{{ route("restaurant.booking", [$restaurant, $booking]) }}">
                                        {{ $booking->name }}
                                    </a>
                                </th>
                                <td class="p-3">{{ $booking->covers }}</td>
                                <td class="p-3">{{ $booking->tableNumber }}</td>
                                <td class="p-3">
                                    {{ $booking->booked_at->toDayDateTimeString() }}
                                </td>
                                <td class="p-3">
                                    {{ $booking->finish_at->format("h:ia") }}
                                </td>
                                <td class="p-3">{{ $booking->comments }}</td>
                                @switch($booking->status)
                                    @case("confirmed")
                                    <td class="text-green-400"><x-icons.check class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</td>
                                    @break
                                    @case("rejected")
                                    @case("cancelled")
                                    <td class="text-red-600"><x-icons.cross class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</td>
                                    @break
                                    @default
                                    <td class="text-yellow-300"><x-icons.info class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</td>
                                    @break
                                @endswitch
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="my-6">
                    {!! $bookings->links() !!}
                </div>
            @break
        @endswitch
    </div>

    <x-jet-dialog-modal wire:model="createBooking" maxWidth="lg">
        <x-slot name="title">
            Create Booking
        </x-slot>
        <x-slot name="content">
            @if(!empty($newBooking))
                <div class="space-y-2">
                    <div class="flex pt-3">
                        <div class="px-2">
                            <x-icons.enter class="h-6"/>
                        </div>
                        <div>
                            <h5 class="font-bold text-lg">{{ $newBooking->booked_at->toDayDateTimeString() }}</h5>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.exit class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input class="w-full" type="datetime-local" wire:model="newBooking.finish_at" :min="$newBooking->booked_at->format('Y-m-d\TH:i')" :max="$nextBooking ? $nextBooking->booked_at->format('Y-m-d\TH:i') : ''" />
                            @error("newBooking.finish_at") <p class="text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.group class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input class="w-auto" type="number" wire:model="newBooking.covers" min="1" /> guests
                            @if($newBooking->covers > $newBooking->tableNumber->seats)
                                <p class="text-gray-500">If another table is being used for this booking, please book out that table too!</p>
                            @endif
                            @error("newBooking.covers") <p class="text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.user class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input type="text" wire:model="newBooking.name" class="w-full" placeholder="Name" />
                            @error("newBooking.name")<span class="text-red-600">{{ $message }}</span>@enderror
                            <x-jet-input type="email" wire:model="newBooking.email" class="w-full" placeholder="Email" />
                            @error("newBooking.email")<span class="text-red-600">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.edit class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input type="tel" wire:model="newBooking.contact_number" class="w-full" placeholder="Contact Number" />
                            @error("newBooking.contact_number")<span class="text-red-600">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.phone class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input textarea wire:model="newBooking.comments" class="w-full" placeholder="Additional Comments"/>
                            @error("newBooking.comments")<span class="text-red-600">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            @else
                <p>Something went wrong!</p>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-button class="bg-red-800 hover:bg-red-700" wire:click="submitBooking">Save</x-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
