<div x-data="{ open: @entangle('open'), modal: false }">
    <x-jet-form-section submit="submit">
        <x-slot name="title">
            Service Hours
        </x-slot>

        <x-slot name="description">
            Update your Restaurants Service Hours.
            <br>
            <x-button x-on:click="modal = true" class="bg-red-800 hover:bg-red-700 mt-3">Exceptions @if($count = $restaurant->service_exceptions->count())<span class="bg-white rounded-full ml-3 text-black px-2">{{ $count }}</span>@endif</x-button>
        </x-slot>

        <x-slot name="form">
            <div x-show.transition="open" class="col-span-6">
                <div class="h-16 space-x-8 flex mb-5 overflow-auto">
                    @foreach(\App\Models\Service::DAYS_OF_WEEK as $day)
                        <x-jet-nav-link wire:click.prevent="changeTab('{{ $day }}')" href="#" :active="$openDay === $day">
                            {{ $day }}
                        </x-jet-nav-link>
                    @endforeach
                </div>

                @foreach(\App\Models\Service::DAYS_OF_WEEK as $day)
                    <div class="space-y-4 {{ $openDay === $day ? '' : 'hidden' }}">
                        @foreach($services->where("day", $day)->sortBy("open") as $index => $service)
                            <div class="grid grid-cols-4 gap-2">
                                <div class="space-y-2">
                                    <div>
                                        <label>Title</label>
                                        <x-jet-input wire:model="services.{{ $index }}.title" placeholder="Title" class="w-full" required/>
                                        @error("services.$index.title")<span class="text-red-600">{{ $message }}</span>@enderror
                                    </div>
                                    <div>
                                        <label>Description</label>
                                        <x-jet-input textarea wire:model="services.{{ $index }}.description" placeholder="Description" class="w-full"/>
                                        @error("services.$index.description")<span class="text-red-600">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div>
                                    <label>Open</label>
                                    <x-jet-input type="time" wire:model="services.{{ $index }}.start" class="w-full" required/>
                                    @error("services.$index.start")<span class="text-red-600">{{ $message }}</span>@enderror
                                </div>
                                <div class="space-y-2">
                                    <div>
                                        <label>Finish</label>
                                        <x-jet-input type="time" wire:model="services.{{ $index }}.finish" class="w-full" required/>
                                        @error("services.$index.finish")<span class="text-red-600">{{ $message }}</span>@enderror
                                    </div>
                                    <div>
                                        <label>Last Booking</label>
                                        <x-jet-input type="time" wire:model="services.{{ $index }}.last_booking" class="w-full" required/>
                                        @error("services.$index.last_booking")<span class="text-red-600">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div>
                                    <x-button wire:click="removeService({{ $index }})" class="bg-red-600 hover:bg-red-500 w-full justify-center">
                                        <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Delete
                                    </x-button>
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-center">
                            <x-button wire:click="addService('{{ $day }}')" class="bg-red-800 hover:bg-red-700">Add Service</x-button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div x-show.transition="!open" class="col-span-6 text-right">
                <x-button @click="open = true" class="bg-red-800 hover:bg-red-700">
                    <svg class="h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Show
                </x-button>
            </div>
        </x-slot>

        @if($open)
            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-button wire:click.prevent="$set('schedule', true)" class="bg-red-800 hover:bg-red-700 mr-3">
                    <x-icons.clock class="h-4 mr-2"/> Schedule
                </x-button>

                <x-button @click="open = false" class="bg-red-800 hover:bg-red-700 mr-3">
                    <svg class="h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    Hide
                </x-button>

                <x-jet-button wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-jet-button>
            </x-slot>
        @endif
    </x-jet-form-section>

    <x-jet-dialog-modal wire:model="schedule">
        <x-slot name="title">Schedule Changes</x-slot>
        <x-slot name="content">
            <p>
                This will schedule the changes to service hours until a later date. The changes will be updated
                immediately in your settings but exceptions will be added up until the scheduled date.
            </p>
            <p class="font-bold">
                Please note: any dates that already have exceptions will be skipped by this process.
            </p>
            <p>To make these changes immediately, leave this field blank and save your changes as normal.</p>

            <div class="flex items-center justify-center">
                <x-jet-input wire:model="scheduleDate" type="date" :min="\Carbon\Carbon::now()->addDay()->format('Y-m-d')"/>
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
        </x-slot>
        <x-slot name="footer">
            <x-button wire:click.prevent="submit" class="bg-red-800 hover:bg-red-700">Save</x-button>
        </x-slot>
    </x-jet-dialog-modal>

    <div x-cloak x-show="modal" x-on:keydown.escape="modal = false" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div x-on:click="modal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                @livewire('restaurant.service-exceptions', compact(["restaurant"]))
            </div>

        </div>
    </div>
</div>
