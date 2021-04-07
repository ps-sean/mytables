<div x-cloak x-data="{ open: @entangle('restaurant_id'), status: @entangle('status') }" class="container mx-auto bg-white p-5 space-y-5">
    @foreach($restaurants as $restaurant)
        <div class="bg-white rounded overflow-hidden shadow hover:shadow-lg cursor-pointer transition-shadow duration-150 ease-in-out">
            <div x-on:click="open = '{{ $restaurant->id }}'" class="p-3 grid grid-cols-3 gap-y-3">
                <h5 class="text-lg font-bold col-span-2 md:col-span-1">{{ $restaurant }}</h5>
                <div class="md:order-last justify-end">
                    <x-restaurant.status :restaurant="$restaurant"/>
                </div>
                <div class="col-span-3 md:col-span-1">
                    @empty($restaurant->email_verified_at)
                        <p class="text-orange-500">Email Not Verified</p>
                    @else
                        <p class="text-green-500">Email Verified</p>
                    @endempty
                </div>
            </div>
            <div x-show.transition.in="open === '{{ $restaurant->id }}'">
                <div class="grid md:grid-cols-2">
                    <div>
                        <div class="grid lg:grid-cols-2">
                            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $restaurant->image }}');"></div>
                            <div class="h-48">
                                <iframe class="w-full h-full"
                                        frameborder="0" style="border:0"
                                        src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.key') }}&q={{ $restaurant->lat }},{{ $restaurant->lng }}"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                        <div class="p-3 space-y-2">
                            <p class="text-gray-700 text-base flex items-start">
                                <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $restaurant->address }}
                            </p>
                            <p class="text-gray-700 text-base flex items-start">
                                <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $restaurant->phone }}
                            </p>
                            <p class="text-gray-700 text-base flex items-start">
                                <svg class="h-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                                {{ $restaurant->email }}&nbsp;
                                @if(!empty($restaurant->email_verified_at))
                                    <span class="text-green-500">({{ $restaurant->email_verified_at->toDayDateTimeString() }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-100 p-3 space-y-2 overflow-auto min-h-full lg:h-64">
                        @foreach($restaurant->activities->reverse()->take(5) as $activity)
                            <div class="grid grid-cols-2 lg:grid-cols-3 py-1 {{ $loop->last ? '' : 'border-b' }}">
                                <div class="col-span-2 lg:col-span-1">
                                    <h5 class="font-bold">{{ ucwords($activity->description) }}</h5>
                                    <p>{{ $activity->created_at->format("d/m/Y") }}</p>
                                    <p>{{ $activity->created_at->format("H:i:s") }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="grid grid-cols-2">
                                        @foreach($activity->changes['old'] as $key => $value)
                                            @if($key !== "updated_at" && $activity->changes['old'][$key] !== $activity->changes['attributes'][$key])
                                                <p class="col-span-2">{{ ucwords($key) }}</p>
                                                @if($key === "status")
                                                    <p class="text-red-500">{{ $activity->changes['old'][$key]['text'] }}</p>
                                                    <p class="text-green-500">{{ $activity->changes['attributes'][$key]['text'] }}</p>
                                                @else
                                                    <p class="text-red-500">{{ $activity->changes['old'][$key] }}</p>
                                                    <p class="text-green-500">{{ $activity->changes['attributes'][$key] }}</p>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <button x-on:click.stop="status = 'rejected'" class="bg-red-600 text-white py-3 font-bold">
                        Reject
                    </button>
                    <button x-on:click.stop="status = 'complete'" class="bg-green-400 text-white py-3 font-bold">
                        Accept
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    <div x-show.transition="status !== null" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div x-on:click="status = null" class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div x-show="status === 'complete'">
                    <div class="bg-white p-3">
                        <p>This will update this restaurant's status to "complete". Are you sure?</p>
                    </div>

                    <button wire:click.prevent="save" class="bg-green-400 text-white py-3 w-full">Accept</button>
                </div>
                <div x-show="status === 'rejected'">
                    <div class="bg-white p-3">
                        <p>This will update this restaurant's status to "rejected". Are you sure?</p>
                    </div>

                    <button wire:click.prevent="save" class="bg-red-600 text-white py-3 w-full">Reject</button>
                </div>
            </div>
        </div>
    </div>
</div>
