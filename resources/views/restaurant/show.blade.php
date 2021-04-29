<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $restaurant->name }}
        </h2>
    </x-slot>

    <div class="container mx-auto bg-white">
        <div class="grid md:grid-cols-2">
            <div class="relative bg-cover bg-center h-64 md:h-auto" style="background-image: url('{{ $restaurant->image }}');">
                @if(!empty($restaurant->image_location) && !empty($restaurant->logo_location))
                    <img class="absolute bottom-0 right-0 max-h-1/2 max-w-1/2" src="{{ $restaurant->logo }}"/>
                @endif
            </div>

            <div class="p-5 space-y-2">
                <p class="text-gray-700 text-base flex items-start">
                    <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $restaurant->address }}
                </p>
                <div class="md:flex">
                    <p class="md:w-1/2 text-gray-700 text-base flex items-start">
                        <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $restaurant->phone }}
                    </p>
                    <p class="md:w-1/2 text-gray-700 text-base flex items-start">
                        <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        {{ $restaurant->email }}
                    </p>
                </div>
                <div class="text-gray-700 text-base flex items-center">
                    <x-icons.star class="h-5 inline mr-2"/>
                    <x-restaurant.reviews :restaurant="$restaurant"/>
                </div>
                <div class="flex text-gray-700">
                    <div class="w-1/12">
                        <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="w-11/12">
                        @foreach(\App\Models\Service::DAYS_OF_WEEK as $day)
                            <div class="grid grid-cols-3 {{ date("D") === $day ? 'font-bold' : '' }}">
                                <p>{{ $day }}:</p>
                                @if(empty($restaurant->open_hours[$day]['open']) || empty($restaurant->open_hours[$day]['close']))
                                    <p class="col-span-2 text-center">Closed</p>
                                @else
                                    <p class="col-span-2 text-center">{{ $restaurant->open_hours[$day]['open'] }} - {{ $restaurant->open_hours[$day]['close'] }}</p>
                                @endif
                            </div>
                        @endforeach
                        @if($specialHours->count())
                            <div class="mt-2 italic">
                                <h5>Change Of Hours</h5>
                                @foreach($specialHours as $hours)
                                    <div class="grid grid-cols-3 {{ date("D") === $hours->open_date->shortEnglishDayOfWeek ? 'font-bold' : '' }}">
                                        <p>{{ $hours->open_date->isoformat("ddd Do MMMM") }}:</p>
                                        @if(empty($hours->open) || empty($hours->close))
                                            <p class="col-span-2 text-center">Closed</p>
                                        @else
                                            <p class="col-span-2 text-center">{{ substr($hours->open, 0, 5) }} - {{ substr($hours->close, 0, 5) }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($restaurant->description))
            <div class="text-lg text-gray-800 text-center border-t border-b border-gray-400 my-3 mx-5 py-5 whitespace-pre-line">{{ trim($restaurant->description) }}</div>
        @endif

        <iframe class="w-full h-48"
                frameborder="0" style="border:0"
                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.key') }}&q={{ $restaurant->lat }},{{ $restaurant->lng }}"
                allowfullscreen>
        </iframe>

        @livewire("restaurant.book", compact(["restaurant"]))
    </div>
</x-app-layout>
