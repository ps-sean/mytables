<div class="relative cursor-pointer" wire:click="showNotifications">
    <svg class="w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>
    @if($count)
        <span class="absolute top-0 left-0 -mt-3 ml-3 bg-red-800 text-white text-xs rounded-full px-2">{{ $count }}</span>
    @endif

    @push("scripts")
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                @this.on("tone", () => {
                    new Audio('{{ asset("audio/notification.mp3") }}').play()
                })
            })
        </script>
    @endpush
</div>
