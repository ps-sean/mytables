<div x-data="{ open: @entangle('showNotifications').live }">
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div x-cloak x-show.immediate="open" class="fixed inset-0 overflow-hidden z-50">
        <div class="absolute inset-0 overflow-hidden">
            <!--
              Background overlay, show/hide based on slide-over state.

              Entering: "ease-in-out duration-500"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in-out duration-500"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div class="absolute inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
            <section x-show.transition="open" class="absolute inset-y-0 right-0 pl-10 max-w-full flex" aria-labelledby="slide-over-heading">
                <!--
                  Slide-over panel, show/hide based on slide-over state.

                  Entering: "transform transition ease-in-out duration-500 sm:duration-700"
                    From: "translate-x-full"
                    To: "translate-x-0"
                  Leaving: "transform transition ease-in-out duration-500 sm:duration-700"
                    From: "translate-x-0"
                    To: "translate-x-full"
                -->
                <div @click.away="open = false" class="relative w-screen max-w-md">
                    <!--
                      Close button, show/hide based on slide-over state.

                      Entering: "ease-in-out duration-500"
                        From: "opacity-0"
                        To: "opacity-100"
                      Leaving: "ease-in-out duration-500"
                        From: "opacity-100"
                        To: "opacity-0"
                    -->
                    <div class="absolute top-0 left-0 -ml-8 pt-4 pr-2 flex sm:-ml-10 sm:pr-4">
                        <button @click="open = false" class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="sr-only">Close panel</span>
                            <!-- Heroicon name: x -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="h-full flex flex-col py-6 bg-white shadow-xl overflow-y-scroll">
                        <div class="px-4 sm:px-6">
                            <div class="flex justify-between">
                                <h2 id="slide-over-heading" class="text-lg font-medium text-gray-900">
                                    Notifications
                                </h2>
                                <div class="flex space-x-2">
                                    <button wire:click.prevent="markAllRead" class="text-blue-400 underline text-sm">Mark All As Read</button>
                                    <p class="flex items-center bg-red-800 text-white text-xs rounded-full px-2">{{ $count }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 relative flex-1 border-t">
                            @if($notifications->count())
                                @foreach($notifications as $notification)
                                    <div wire:click="followNotification('{{ $notification->id }}')" class="w-full p-3 cursor-pointer transition-all border-b duration-150 ease-in-out hover:bg-red-500 hover:text-white {{ $notification->read() ? '' : 'bg-red-800 text-white' }}">
                                        <div class="space-y-2">
                                            @if(!empty($notification->data['title']))
                                                <h5 class="font-bold">{{ $notification->data['title'] }}</h5>
                                            @endif
                                            <p>{{ $notification->data['text'] }}</p>
                                            <p class="text-sm {{ $notification->read() ? 'text-gray-500': 'text-gray-100' }}">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="italic text-center my-3">No notifications</p>
                            @endif
                        </div>
                        <div class="mt-6 relative flex-2 px-4 sm:px-6">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</div>
