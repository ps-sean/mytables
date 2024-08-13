<form class="grid md:grid-cols-2 gap-x-12 gap-y-8 px-6">
    <div>
        <label class="block">Restaurant Name</label>
        <x-input wire:model.live="name" class="w-full" placeholder="Your Amazing Restaurant Name"/>
        @error("name") <p class="text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="relative">
        <label class="block">Restaurant Address</label>
        <div class="relative">
            <x-input class="w-full" wire:model.live="address" placeholder="The Street You Light Up" disabled="{{ !empty($addressJSON) }}"/>
            <button wire:click.prevent="resetSession" class="absolute inset-y-0 right-0 text-red-800 font-black hover:text-red-600 text-3xl focus:outline-none">&times;</button>
        </div>
        <div class="absolute">
            @error("address") <p class="text-red-600">{{ $message }}</p> @else <img class="h-4 mt-2" src="{{ asset("img/powered_by_google/desktop/powered_by_google_on_white_hdpi.png") }}" alt="powered by Google"/> @enderror
        </div>
        @if(!empty($addressResults) || (!empty($address) && empty($addressJSON)))
        <div class="absolute bg-white w-full border-l border-r shadow-lg">
            @empty($addressResults)
                <p class="px-2 py-1">We couldn't find your address. Please <a class="text-red-800 hover:text-red-600" href="{{ route('contact') }}">contact us</a> to add your restaurant manually.</p>
            @else
                <ul>
                    @foreach($addressResults as $result)
                        <li wire:click="selectPlace({{ json_encode($result->place_id) }})" class="py-1 px-2 border-b hover:bg-red-800 hover:text-white cursor-pointer">{{ $result->description }}</li>
                    @endforeach
                </ul>
            @endempty
        </div>
        @endif
        <x-input type="hidden" wire:model.live="sessionToken"/>
        <x-input type="hidden" wire:model.live="addressJSON"/>
    </div>
    <div>
        <label class="block">Contact Number</label>
        <x-input wire:model.live="phone" type="tel" class="w-full" placeholder="Just in case we need to check things over" maxlength="16"/>
        @error("phone") <p class="text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block">Email Address</label>
        <x-input wire:model.live="email" type="email" class="w-full" placeholder="We'll send you an email to confirm your details"/>
        @error("email") <p class="text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <p class="text-left text-sm mb-3">myTables partners with <a class="text-red-800 hover:text-red-600" href="https://stripe.com">Stripe</a> to make sure you get paid securely and on time. You will be taken through the stripe onboarding process in the next step.</p>
        <p class="text-red-600">
            @error("submit_fail") {{ $message }} @enderror
        </p>
    </div>
    <div class="text-right">
        <x-button class="bg-red-800 hover:bg-red-700 text-white" wire:loading.attr="disabled" wire:click.prevent="submit">Start Your Journey</x-button>
    </div>
</form>
