<form wire:submit="submit" class="space-y-5">
    <div>
        <label class="text-gray-500">Name</label>
        <x-jet-input class="w-full" wire:model.live="name"/>
        @error("name")<span class="text-red-600">{{ $message }}</span>@enderror
    </div>
    <div>
        <label class="text-gray-500">Email</label>
        <x-jet-input type="email" class="w-full" wire:model.live="email"/>
        @error("email")<span class="text-red-600">{{ $message }}</span>@enderror
    </div>
    <div>
        <label class="text-gray-500">Subject</label>
        <x-select wire:model.live="subject" required class="w-full" :disabled="$subjectDisabled">
            @if(empty($subject))
                <option>- Please Select -</option>
            @endif
            <option>Booking Enquiry</option>
        </x-select>
        @error("subject")<span class="text-red-600">{{ $message }}</span>@enderror
    </div>
    <div>
        <label class="text-gray-500">Your Message</label>
        <x-jet-input textarea wire:model.live="text" class="w-full" rows="5"/>
        @error("text")<span class="text-red-600">{{ $message }}</span>@enderror
    </div>
    <div class="text-center">
        <x-jet-button wire:loading.attr="disabled">Send</x-jet-button>
        <x-jet-action-message class="text-green-500" on="sent">Mail Sent Successfully.</x-jet-action-message>
        <x-jet-action-message class="text-red-600" on="failed">Mail Failed to Send.</x-jet-action-message>
    </div>
</form>
