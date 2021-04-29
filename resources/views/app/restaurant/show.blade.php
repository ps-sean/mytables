<x-frame-app-layout>
    <div class="relative w-full h-64 bg-cover bg-center" style="background-image: url({{ $restaurant->image }});">
        @if(!empty($restaurant->image_location) && !empty($restaurant->logo_location))
            <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $restaurant->logo }}"/>
        @endif
    </div>

    <div class="p-5 space-y-5">
        <div class="flex overflow-auto rounded border mx-auto max-w-full" style="width: fit-content;">
            @foreach($dates as $date)
                <a href="?date={{ $date->format("Y-m-d") }}&covers={{ $covers }}&group={{ $group }}" class="booking_date_choice py-3 px-5 text-center cursor-pointer hover:bg-red-700 hover:text-white transition duration-500 ease-in-out {{ $date->isSameDay($selectedDate) ? 'bg-red-800 text-white shadow-inner' : '' }} {{ $loop->last ? '' : 'border-r' }}">
                    {{ $date->isoFormat("ddd") }}<br>
                    {{ $date->isoFormat("Do") }}<br>
                    {{ $date->isoFormat("MMM") }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center justify-center">
                <a href="?date={{ $selectedDate }}&covers={{ $covers > 1 ? $covers - 1 : $covers }}&group={{ $group }}">
                    <x-button class="bg-red-800 hover:bg-red-700 rounded-r-none">&minus;</x-button>
                </a>
                <p class="px-3">{{ $covers }} {{ \Illuminate\Support\Str::plural("guest", $covers) }}</p>
                <a href="?date={{ $selectedDate }}&covers={{ $covers + 1 }}&group={{ $group }}">
                    <x-button class="bg-red-800 hover:bg-red-700 rounded-l-none">&plus;</x-button>
                </a>
            </div>
            <div class="flex items-center justify-center">
                <label>Location:</label>
                <x-select id="location_picker">
                    @foreach($restaurant->table_groups()->whereHas("tables", function($query){return $query->where("bookable", 1);})->get() as $location)
                        <option value="{{ $location->id }}" {{ $group == $location->id ? 'selected' : '' }}>{{ $location }}</option>
                    @endforeach
                    <option value="all" {{ $group == "all" ? 'selected' : '' }}>All</option>
                </x-select>
            </div>
        </div>

        @if($covers > $restaurant->max_booking_size($group))
            <div class="lg:wd-1/2 space-y-5">
                <p>{{ $restaurant }} can only take bookings of up to {{ $restaurant->max_booking_size($group) }} guests online in the {{ \App\Models\TableGroup::find($group) }}. Please contact the restaurant directly or go to <a class="external_link text-red-800 hover:text-red-700" href="{{ config("app.url") }}/restaurant/{{ $restaurant->id }}">myTables</a>.</p>
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @if($services->count())
                    @foreach($services as $serviceTime)
                        <a href="/app/restaurant/{{ $restaurant->id }}/book?time={{ urlencode($serviceTime->time) }}&covers={{ $covers }}&group={{ $group }}" class="bg-white shadow rounded p-5 text-center hover:bg-red-800 hover:text-white transition ease-in-out duration-500 cursor-pointer">
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

    @push("scripts")
        <script>
            let locationpicker = document.getElementById("location_picker")
            let externallinks = document.getElementsByClassName("external_link")

            locationpicker.addEventListener("change", (e) => {
                window.location.replace("?date={{ $selectedDate }}&covers={{$covers }}&group=" + e.target.value)
            })

            for(let i = 0; i < externallinks.length; i++){
                externallinks[i].addEventListener("click", (e) => {
                    e.preventDefault()

                    window.top.location.href = e.target.href
                })
            }
        </script>
    @endpush
</x-frame-app-layout>
