<div x-data="{ show: false }">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bookings - {{ $restaurant->name }}
            </h2>
        </div>
    </x-slot>
    <div class="bg-white p-5">
        <div class="container mx-auto md:flex justify-between gap-3 mb-6">
            <x-button class="bg-red-800 hover:bg-red-700" wire:loading.attr="disabled" wire:click="createNewBooking">New Booking</x-button>
            <x-jet-input class="w-full md:w-auto" wire:model="search" placeholder="Search"/>
            @if($view === "list")
                <div class="w-full md:w-auto">
                    Status:&nbsp;
                    <x-select wire:model="status">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="rejected">Rejected</option>
                        <option value="no show">No Show</option>
                        <option value="seated">Seated</option>
                    </x-select>
                </div>
            @else
                <x-jet-input class="w-full md:w-auto" wire:model="date" type="date"/>
                <div class="relative flex items-center">
                    <a class="block flex items-center border-b border-gray-300 py-2 w-full md:w-auto justify-between space-x-2" x-on:click.prevent="show = !show" href="#show"><span>Show Info ({{ count($show) }})</span><x-icons.chevron-down class="h-4"/></a>
                    <div class="absolute top-full left-0 w-full md:w-32 bg-white p-2 rounded-b border border-gray-300" x-show.transition.in="show" x-cloak x-on:click.away="show = false">
                        <ul class="list-inside">
                            @foreach(["name", "guests", "table", "time", "comments"] as $s)
                                <li>
                                    <label>
                                        <input type="checkbox" wire:model="show" value="{{ $s }}">
                                        {{ ucwords($s) }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="w-full md:w-auto text-center md:text-right">
                    Text:&nbsp;
                    <x-select wire:model="size">
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                    </x-select>
                </div>
            @endif
            <div class="w-full md:w-auto text-center md:text-right">
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
                    <div class="flex justify-center text-center">
                        <p class="text-3xl">No services for this date.</p>
                    </div>
                @else
                    <div class="overflow-auto {{ $sizes[$size]['text'] }}">
                        <table>
                            <thead>
                            <tr class="bg-red-800 text-white block">
                                <th class="border" style="min-width: 200px;"></th>
                                @foreach($period as $time)
                                    @if($loop->index%4 === 0)
                                        <th class="border text-left p-1 overflow-hidden" colspan="{{ $loop->remaining >= 4 ? 4 : ($loop->remaining + 1) }}" style="min-width: {{ $sizes[$size]['col']*($loop->remaining >= 4 ? 4 : ($loop->remaining+1)) }}px;max-width: {{ $sizes[$size]['col']*($loop->remaining >= 4 ? 4 : ($loop->remaining+1)) }}px;">{{  $time->format("h:ia") }}</th>
                                    @endif
                                @endforeach
                            </tr>
                            <tr class="block">
                                <th class="text-left p-1 border bg-gray-600 text-white border-white" colspan="{{ $period->count() + 1 }}" style="min-width: {{ $sizes[$size]['col']*($period->count() + 4) }}px;">Services</th>
                            </tr>
                            @if($services->count())
                                @foreach($services as $service)
                                    @php($serviceStart = \Carbon\Carbon::parse($date . " " . $service->start->format("H:i:s")))
                                    <tr class="block">
                                        <th class="border p-1 {{ $loop->even ? '' : 'bgr-gray-100' }}" style="min-width:200px;">{{ $service }}</th>
                                        @php($cols = 0)
                                        @foreach($period as $time)
                                            @if($cols < 1)
                                                @if($time->equalTo($serviceStart))
                                                    @php($cols = $service->columns())
                                                    <td class="border bg-red-800 text-white text-center" colspan="{{ $cols }}" style="min-width: {{ $sizes[$size]['col']*$cols }}px;">{{ \Carbon\Carbon::parse(date("Y-m-d " . $service->start->format("H:i:s")))->format("h:ia") }} - {{ \Carbon\Carbon::parse(date("Y-m-d " . $service->finish->format("H:i:s")))->format("h:ia") }}</td>
                                                @else
                                                    <td class="border bg-gray-300 border-white" style="min-width: {{ $sizes[$size]['col'] }}px;"></td>
                                                @endif
                                            @endif
                                            @php($cols--)
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                <tr class="block">
                                    <td class="border text-center p-1 bg-red-800 text-white" colspan="{{ $period->count() + 1 }}" style="min-width: {{ $sizes[$size]['col']*($period->count() + 4) }}px;">No Services</td>
                                </tr>
                            @endif
                            <tr class="block">
                                <th class="text-left p-1 border bg-gray-600 text-white border-white" colspan="{{ $period->count() + 1 }}" style="min-width: {{ $sizes[$size]['col']*($period->count() + 4) }}px;">Bookings</th>
                            </tr>
                            </thead>
                            <tbody class="block max-h-75vh overflow-auto">
                            @foreach($tables as $table)
                                @php($bgGray = $loop->even)
                                <tr class="block">
                                    <th class="border p-1 {{ $loop->even ? '' : 'bg-gray-100' }}" style="min-width:200px;max-width:200px;">{{ $table }}</th>
                                    @php($cols = 0)
                                    @foreach($period as $time)
                                        @if($cols < 1)
                                            @if($booking = $this->fetchBooking($reservations, $table, $time))
                                                @php($cols = $booking->columns())
                                                <td class="border p-0" data-covers="{{ $booking->covers }}" colspan="{{ $cols }}" style="min-width: {{ $sizes[$size]['col']*$cols }}px;">
                                                    <a class="block h-full" href="{{ route("restaurant.booking", [$restaurant, $booking]) }}">
                                                        <div  class="relative w-full h-full p-1 hover:bg-red-700 hover:text-white transition-all duration-150 ease-in-out {{ (!empty($search) && preg_match("/{$search}/i", $booking->name)) ? 'bg-red-800 text-white scroll-me' : ($bgGray ? 'bg-gray-100' : '') }}">
                                                            <div class="grid grid-cols-2">
                                                                @if(in_array("name", $show) || in_array("guests", $show))
                                                                    <h5 class="flex space-x-2">
                                                                        @if(in_array("name", $show))
                                                                            <span class="font-bold">{{ $booking->name }}</span>
                                                                        @endif
                                                                        @if(in_array("guests", $show))
                                                                            <span class="flex">
                                                                                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                </svg>
                                                                                {{ $booking->covers }}
                                                                            </span>
                                                                        @endif
                                                                    </h5>
                                                                @endif
                                                                @if(in_array("table", $show))
                                                                    <p class="text-right">{{ $booking->tableNames }}</p>
                                                                @endif
                                                                @if(in_array("time", $show))
                                                                    <p class="col-span-2">{{ $booking->booked_at->format("h:ia") }} - {{ $booking->finish_at->format("h:ia") }}</p>
                                                                @endif
                                                                @if(in_array("comments", $show) && !empty($booking->comments))
                                                                    <div class="col-span-2">
                                                                        @if(strlen($booking->comments) > 25)
                                                                            <p title="{{ $booking->comments }}">{{ substr($booking->comments, 0, 25) }}...</p>
                                                                        @else
                                                                            <p>{{ $booking->comments }}</p>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            @if($booking->status === "pending")
                                                                <div class="flex justify-center items-center absolute inset-0 bg-gray-300/75 text-gray-700 hover:text-gray-500">
                                                                    <i>Pending</i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </td>
                                                @php($bgGray = !$bgGray)
                                            @else
                                                <td wire:click="createNewBooking('{{ $time }}', {{ $table->id }})" x-data="{ hover: false }" x-on:mouseenter="hover = true" x-on:mouseleave="hover = false" class="border border-white bg-gray-300 text-center cursor-pointer justify-center align-middle text-white hover:bg-red-800 transition-all ease-in-out duration-150" style="min-width: {{ $sizes[$size]['col'] }}px;">
                                                    <x-icons.plus x-cloak x-show.transition.in="hover" class="h-8 mx-auto"/>
                                                </td>
                                            @endif
                                        @endif
                                        @php($cols--)
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="bg-red-800 text-white block">
                                <th class="border" style="min-width: 200px;"></th>
                                @foreach($period as $time)
                                    @if($loop->index%4 === 0)
                                        <th class="border text-left p-1 overflow-hidden" colspan="{{ $loop->remaining >= 4 ? 4 : ($loop->remaining + 1) }}" style="min-width: {{ $sizes[$size]['col']*($loop->remaining >= 4 ? 4 : ($loop->remaining+1)) }}px;max-width: {{ $sizes[$size]['col']*($loop->remaining >= 4 ? 4 : ($loop->remaining+1)) }}px;">{{  $time->format("h:ia") }}</th>
                                    @endif
                                @endforeach
                            </tr>
                            </tfoot>
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
                        @foreach($reservations as $booking)
                            <tr class="{{ $booking->booked_at->isPast() ? 'opacity-50' : '' }} {{ $loop->even ? 'bg-gray-100' : '' }} border-b border-gray-500">
                                <th class="p-3 text-left">
                                    <a class="text-red-800 hover:text-red-500 underline transition-all duration-150 ease-in-out" href="{{ route("restaurant.booking", [$restaurant, $booking]) }}">
                                        {{ $booking->name }}
                                    </a>
                                </th>
                                <td class="p-3">{{ $booking->covers }}</td>
                                <td class="p-3">{{ $booking->tableNames }}</td>
                                <td class="p-3">
                                    {{ $booking->booked_at->toDayDateTimeString() }}
                                </td>
                                <td class="p-3">
                                    {{ $booking->finish_at->format("h:ia") }}
                                </td>
                                <td class="p-3">{{ $booking->comments }}</td>
                                @switch($booking->status)
                                    @case("confirmed")
                                    @case("seated")
                                    <td class="text-green-400"><x-icons.check class="h-5 inline mr-2"/> {{ ucwords($booking->status) }}</td>
                                    @break
                                    @case("rejected")
                                    @case("cancelled")
                                    @case("no show")
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
                    {!! $reservations->links() !!}
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
                    <ul class="list-inside list-disc text-red-600">
                        @foreach($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <div class="flex pt-3">
                        <div class="px-2">
                            <x-icons.enter class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input class="w-full" type="datetime-local" wire:model="newBooking.booked_at" />
                            @error("newBooking.booked_at") <p class="text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.exit class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input class="w-full" type="datetime-local" wire:model="newBooking.finish_at" :min="$newBooking->booked_at->format('Y-m-d\TH:i')" />
                            <small>This field auto updates when you edit the booking time or guests.</small>
                            @error("newBooking.finish_at") <p class="text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.table class="h-6"/>
                        </div>
                        <div class="w-full">
                            <div class="border border-gray-200 rounded-sm w-full h-48 overflow-auto p-2 space-y-2 shadow-inner">
                                @foreach($restaurant->tables as $table)
                                    <div>
                                        <label class="block">
                                            <input type="checkbox" wire:model="newBookingTables.{{ $table->getKey() }}" />
                                            {{ $table }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error("newBookingTables") <p class="text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>


                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.group class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input class="w-auto" type="number" wire:model="newBooking.covers" min="1" /> guests
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
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.at class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input type="email" wire:model="newBooking.email" class="w-full" placeholder="Email" />
                            @error("newBooking.email")<span class="text-red-600">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.phone class="h-6"/>
                        </div>
                        <div class="w-full">
                            <x-jet-input type="tel" wire:model="newBooking.contact_number" class="w-full" placeholder="Contact Number" />
                            @error("newBooking.contact_number")<span class="text-red-600">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="px-2">
                            <x-icons.edit class="h-6"/>
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
            <div class="flex justify-end gap-2 items-center">
                <div wire:loading.flex wire:target="submitBooking">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </div>
                <x-button class="bg-red-800 hover:bg-red-700" wire:loading.attr="disabled" wire:click="submitBooking">Save</x-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    @push("scripts")
        <script>
            document.addEventListener("livewire:load", () => {
                window.addEventListener("search", () => {
                    let found = document.getElementsByClassName("scroll-me")

                    if(found.length){
                        found[0].scrollIntoView({
                            behavior: "smooth"
                        })
                    }
                })
            })
        </script>
    @endpush
</div>
