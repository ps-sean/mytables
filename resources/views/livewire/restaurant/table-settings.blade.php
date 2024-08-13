<div x-data="{ modal: false, blockModal: false }">
    <x-jet-form-section submit="submit">
        <x-slot name="title">
            Tables
        </x-slot>
        <x-slot name="description">
            Update your Table Details.
            <br>
            <x-button x-on:click="modal = true" class="bg-red-800 hover:bg-red-700 mt-3">Restaurant Sections</x-button>
            <br>
            <x-button x-on:click="blockModal = true" class="bg-red-800 hover:bg-red-700 mt-3">Block Tables</x-button>
        </x-slot>
        <x-slot name="form">
            @if($show)
                @if($tables->count())
                    @foreach($tables as $index => $table)
                        <div class="col-span-2 shadow-lg p-3 space-y-3">
                            <input type="hidden" wire:model.live="tables.*.id">
                            <div>
                                <label class="text-gray-600">Name</label>
                                <x-jet-input class="w-full" wire:model.live="tables.{{ $index }}.name" placeholder="Table Name" required />
                                @error("tables.$index.name")<span class="text-red-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="text-gray-600">Seats</label>
                                <x-jet-input type="number" class="w-full" wire:model.live="tables.{{ $index }}.seats" min="1" required />
                                @error("tables.$index.seats")<span class="text-red-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label>Section</label>
                                <x-select class="w-full" wire:model.live="tables.{{ $index }}.restaurant_section_id">
                                    <option value="">--</option>
                                    @foreach($this->restaurant->sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </x-select>
                                @error("tables.$index.restaurant_section_id")<span class="text-red-600">{{ $message }}</span>@enderror
                            </div>
                            <div class="text-center">
                                <label><input type="checkbox" wire:model.live="tables.{{ $index }}.bookable"/> Bookable</label>
                                @error("tables.$index.bookable")<span class="text-red-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <x-button wire:click.prevent="deleteTable({{ $index }})" class="bg-red-600 hover:bg-red-500 w-full text-center">
                                <span class="mx-auto flex">
                                    <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Delete
                                </span>
                                </x-button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="col-span-6">No table data to show</p>
                @endif
                <div class="col-start-2 col-span-4 text-center space-y-3">
                    <x-button wire:click.prevent="addTable" class="bg-red-800 hover:bg-red-700">Add Table</x-button>
                    <p class="text-sm text-gray-600">Please note: After saving, any new tables on your system will be added to your monthly bill.</p>
                </div>
            @else
                <div class="col-span-6 text-right">
                    <x-button wire:click.prevent="toggleShow" class="bg-red-800 hover:bg-red-700">
                        <svg class="h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Show
                    </x-button>
                </div>
            @endif
        </x-slot>
        @if($show)
            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-button wire:click.prevent="toggleShow" class="bg-red-800 hover:bg-red-700 mr-3">
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

    <div x-cloak x-show="modal" x-on:keydown.escape="modal = false" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div x-on:click="modal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                @livewire('restaurant.sections', compact(["restaurant"]))
            </div>

        </div>
    </div>

    <div x-cloak x-show="blockModal" x-on:keydown.escape="blockModal = false" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div x-on:click="blockModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                @livewire('restaurant.block-tables', compact(["restaurant"]))
            </div>

        </div>
    </div>
</div>
