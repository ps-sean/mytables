<div class="shadow-xl p-3 rounded border border-gray-100" x-data x-init="
            box = document.getElementById('message_box');
            box.scrollTop = box.scrollHeight;

            $wire.on('new-message', () => {
                box.scrollTop = box.scrollHeight;
            });
">
    <div class="bg-gray-300">
        <div class="h-128 p-3 space-y-2 overflow-auto" id="message_box">
            @if($messages->count())
                @php($lastMessage = null)
                @foreach($messages as $message)
                    @if(empty($lastMessage) || !$message->created_at->isSameDay($lastMessage->created_at))
                        <div class="flex justify-center">
                            <p class="text-gray-800 text-xs">{{ $message->created_at->toFormattedDateString() }}</p>
                        </div>
                    @endif
                    <div>
                        <div class="flex {{ $message->me() ? 'justify-end' : '' }}">
                            <div class="h-10 w-10 inline-block rounded-full flex justify-center items-center {{ $message->me() ? 'order-1 ml-1 bg-red-800 text-white' : 'mr-1  bg-white text-gray-600' }}">{{ $message->author->initials }}</div>
                            <div class="{{ $message->me() ? 'bg-red-800 text-white rounded-br-none order-0' : 'bg-white rounded-bl-none' }} px-2 py-1 rounded-xl flex items-center" style="max-width: 75%;">
                                <p>{{ $message->message }}</p>
                            </div>
                        </div>
                        <p class="text-gray-800 text-xs {{ $message->me() ? 'text-right' : 'text-left' }}">{{ $message->created_at->toTimeString() }}</p>
                    </div>
                    @php($lastMessage = $message)
                @endforeach
            @else
                <p class="text-gray-800 text-xs text-center">No messages</p>
            @endif
        </div>
        <div>
            @if($booking->booked_at->isFuture())
                <form wire:submit="submit">
                    <div class="grid grid-cols-4">
                        <x-input textarea class="bg-white col-span-3" placeholder="Type something here..." type="text" wire:model.live="message"/>
                        <x-button type="submit" class="bg-red-800 hover:bg-red-700 rounded-t-none rounded-b-none justify-center">Send</x-button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
