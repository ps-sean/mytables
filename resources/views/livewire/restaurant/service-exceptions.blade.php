@if(!empty($newDate))
    <form wire:submit.prevent="addException">
        <div class="px-4 py-5 bg-white sm:p-6 flex justify-between space-x-3 items-center">
            <div>
                <label>Date</label>
                <x-jet-input class="w-full" type="date" wire:model="newDate" :min="$newDate" required/>
                @error("newDate")<span class="text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="flex items-center space-x-3">
                <x-button wire:click.prevent="$set('newDate', null)" class="bg-gray-500 hover:bg-gray-400">Cancel</x-button>
                <x-jet-button wire:loading.attr="disabled">Save</x-jet-button>
            </div>
        </div>
    </form>
@else
    <form wire:submit.prevent="submit">
        <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 bg-white sm:p-6 space-y-4">
                <div class="flex h-16 space-x-8 overflow-auto">
                    @foreach($exceptions->pluck("service_date")->unique()->sort() as $date)
                        <x-jet-nav-link class="whitespace-no-wrap" wire:click="changeOpenDate('{{ $date->format('Y-m-d') }}')" href="#" :active="$openDate === $date->format('Y-m-d')">{{ $date->toFormattedDateString() }}</x-jet-nav-link>
                    @endforeach
                    <x-jet-nav-link class="whitespace-no-wrap" wire:click="addException" href="#">&plus; Add Exception</x-jet-nav-link>
                </div>

                @foreach($exceptions->where("service_date", \Carbon\Carbon::parse($openDate)) as $index => $exception)
                    @if($exception->closed)
                        <div class="flex items-center justify-center py-6">
                            <label>
                                <input type="checkbox" wire:model="exceptions.{{ $index }}.closed"/> Closed
                                @error("exceptions.$index.closed")<span class="text-red-600">{{ $message }}</span>@enderror
                            </label>
                        </div>
                    @else
                        <div class="grid grid-cols-4 gap-2">
                            <div class="space-y-2">
                                <div>
                                    <label>Title</label>
                                    <x-jet-input wire:model="exceptions.{{ $index }}.title" placeholder="Title" class="w-full"/>
                                    @error("exceptions.$index.title")<span class="text-red-600">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label>Description</label>
                                    <x-jet-input textarea wire:model="exceptions.{{ $index }}.description" placeholder="Description" class="w-full"/>
                                    @error("exceptions.$index.description")<span class="text-red-600">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div>
                                <label>Open</label>
                                <x-jet-input type="time" wire:model="exceptions.{{ $index }}.start" class="w-full"/>
                                @error("exceptions.$index.start")<span class="text-red-600">{{ $message }}</span>@enderror
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <label>Finish</label>
                                    <x-jet-input type="time" wire:model="exceptions.{{ $index }}.finish" class="w-full"/>
                                    @error("exceptions.$index.finish")<span class="text-red-600">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label>Last Booking</label>
                                    <x-jet-input type="time" wire:model="exceptions.{{ $index }}.last_booking" class="w-full"/>
                                    @error("exceptions.$index.last_booking")<span class="text-red-600">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div>
                                <x-button wire:click="removeException({{ $index }})" class="bg-red-600 hover:bg-red-500 w-full justify-center">
                                    <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Delete
                                </x-button>
                            </div>
                        </div>
                    @endif
                @endforeach
                @if(!$exceptions->firstWhere("service_date", \Carbon\Carbon::parse($openDate))->closed)
                    <div class="flex items-center justify-center">
                        <x-button class="justify-center bg-red-800 hover:bg-red-700" type="button" wire:click.prevent="addService"><x-icons.plus class="h-6 mr-2"/> Add Service</x-button>
                    </div>
                @endif
            </div>

            @if($errors->any())
                <x-alert class="border-red-600 text-red-600 bg-red-200">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-jet-button>
            </div>
        </div>
    </form>
@endif
