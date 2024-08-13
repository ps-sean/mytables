<form wire:submit="submit">
    <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 bg-white sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($blocks as $index => $block)
                    <div>
                        <label>Start</label>
                        <x-input class="w-full" type="datetime-local" wire:model.live="blocks.{{ $index }}.start_date"/>
                        @error("blocks.$index.start_date")<span class="text-red-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label>End</label>
                        <x-input class="w-full" type="datetime-local" wire:model.live="blocks.{{ $index }}.end_date"/>
                        @error("blocks.$index.end_date")<span class="text-red-600">{{ $message }}</span>@enderror
                    </div>
                    <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($restaurant->tables()->where("bookable", 1)->get() as $table)
                            <label>
                                <input type="checkbox" wire:model.live="blocks.{{ $index }}.tables" value="{{ $table->id }}"/>
                                {{ $table }}
                            </label>
                        @endforeach
                    </div>
                    @error("blocks.$index.tables")<span class="text-red-600">{{ $message }}</span>@enderror
                    <div class="md:col-span-2 flex justify-center items-center">
                        <x-button class="bg-red-800 hover:bg-red-700" wire:click.prevent="removeBlock('{{ $index }}')"><x-icons.cross class="h-6 mr-2"/> Delete</x-button>
                    </div>
                    <div class="md:col-span-2 border-b border-red-800"></div>
                @endforeach
            </div>
            <div class="flex items-center justify-center my-4">
                <x-button wire:click.prevent="addBlock" class="bg-red-800 hover:bg-red-700">Add Block</x-button>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
            <x-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button class="bg-red-800 hover:bg-red-700 text-white" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </div>
    </div>
</form>
