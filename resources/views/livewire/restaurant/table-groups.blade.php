<form wire:submit.prevent="submit">
    <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 bg-white sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($groups as $key => $group)
                    <div class="rounded border border-gray-300 p-6 space-y-4">
                        <div>
                            <label>Name</label>
                            <x-jet-input class="w-full" wire:model="groups.{{ $key }}.name" required/>
                            @error("groups.$key.name")<span class="text-red-600">{{ $message }}</span>@enderror
                        </div>
                        <x-button wire:click.prevent="removeGroup('{{ $key }}')" class="bg-red-600 hover:bg-red-500 w-full justify-center"><x-icons.cross class="h-6 mr-2"/> Delete</x-button>
                    </div>
                @endforeach
            </div>
            <div class="flex items-center justify-center my-4">
                <x-button wire:click.prevent="addGroup" class="bg-red-800 hover:bg-red-700">Add Group</x-button>
            </div>
        </div>

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
