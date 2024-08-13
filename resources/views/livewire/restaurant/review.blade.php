<div>
    @if(!$booking->review)
        <form wire:submit="submit">
            <div class="grid md:grid-cols-2 gap-4 p-5">
                <div class="space-y-2">
                    <h5 class="font-bold text-xl">Review</h5>
                    <div>
                        <label>Review Title<span class="text-red-600">&ast;</span></label>
                        <x-jet-input class="w-full" maxlength="255" wire:model.live="review.title" placeholder="What is the main reason for your review?"/>
                        @error("review.title")<span class="text-red-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label>Review</label>
                        <x-jet-input rows="10" textarea wire:model.live="review.review" class="w-full" placeholder="Give your review some context"/>
                        @error("review.review")<span class="text-red-600">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="space-y-4">
                    <h5 class="font-bold text-xl">Ratings</h5>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Price</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <a href="#" wire:click.prevent="$set('review.price', 1)"><x-icons.star :filled="$review->price >= 1" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.price', 2)"><x-icons.star :filled="$review->price >= 2" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.price', 3)"><x-icons.star :filled="$review->price >= 3" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.price', 4)"><x-icons.star :filled="$review->price >= 4" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.price', 5)"><x-icons.star :filled="$review->price >= 5" class="h-6"/></a>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Service</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <a href="#" wire:click.prevent="$set('review.service', 1)"><x-icons.star :filled="$review->service >= 1" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.service', 2)"><x-icons.star :filled="$review->service >= 2" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.service', 3)"><x-icons.star :filled="$review->service >= 3" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.service', 4)"><x-icons.star :filled="$review->service >= 4" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.service', 5)"><x-icons.star :filled="$review->service >= 5" class="h-6"/></a>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Food/Drink</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <a href="#" wire:click.prevent="$set('review.product', 1)"><x-icons.star :filled="$review->product >= 1" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.product', 2)"><x-icons.star :filled="$review->product >= 2" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.product', 3)"><x-icons.star :filled="$review->product >= 3" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.product', 4)"><x-icons.star :filled="$review->product >= 4" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.product', 5)"><x-icons.star :filled="$review->product >= 5" class="h-6"/></a>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Cleanliness</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <a href="#" wire:click.prevent="$set('review.cleanliness', 1)"><x-icons.star :filled="$review->cleanliness >= 1" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.cleanliness', 2)"><x-icons.star :filled="$review->cleanliness >= 2" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.cleanliness', 3)"><x-icons.star :filled="$review->cleanliness >= 3" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.cleanliness', 4)"><x-icons.star :filled="$review->cleanliness >= 4" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.cleanliness', 5)"><x-icons.star :filled="$review->cleanliness >= 5" class="h-6"/></a>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Overall<span class="text-red-600">&ast;</span></h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <a href="#" wire:click.prevent="$set('review.overall', 1)"><x-icons.star :filled="$review->overall >= 1" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.overall', 2)"><x-icons.star :filled="$review->overall >= 2" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.overall', 3)"><x-icons.star :filled="$review->overall >= 3" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.overall', 4)"><x-icons.star :filled="$review->overall >= 4" class="h-6"/></a>
                                <a href="#" wire:click.prevent="$set('review.overall', 5)"><x-icons.star :filled="$review->overall >= 5" class="h-6"/></a>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    @error("review.overall")<div><span class="text-red-600">{{ $message }}</span></div>@enderror
                </div>
                <div class="md:col-span-2 text-center">
                    <x-jet-button class="bg-red-800 hover:bg-red-700">Submit</x-jet-button>
                    <x-jet-action-message on="saved" class="text-center my-3">Saved</x-jet-action-message>
                </div>
            </div>
        </form>
    @else
        <div class="grid md:grid-cols-2 gap-4 p-5">
            <div class="space-y-2">
                <h5 class="font-bold text-xl">{{ $booking->review->title }}</h5>
                <p>{{ $booking->review->review }}</p>
            </div>
            <div class="space-y-4">
                <h5 class="font-bold text-xl">Ratings</h5>
                <div class="md:flex md:justify-between items-center">
                    <h5 class="font-bold">Price</h5>
                    <div class="flex justify-center items-center space-x-2">
                        <label>Very Poor</label>
                        <div class="flex space-x-1 items-center text-red-800">
                            <x-icons.star :filled="$booking->review->price >= 1" class="h-6"/>
                            <x-icons.star :filled="$booking->review->price >= 2" class="h-6"/>
                            <x-icons.star :filled="$booking->review->price >= 3" class="h-6"/>
                            <x-icons.star :filled="$booking->review->price >= 4" class="h-6"/>
                            <x-icons.star :filled="$booking->review->price >= 5" class="h-6"/>
                        </div>
                        <label>Very Good</label>
                    </div>
                </div>
                <div class="md:flex md:justify-between items-center">
                    <h5 class="font-bold">Service</h5>
                    <div class="flex justify-center items-center space-x-2">
                        <label>Very Poor</label>
                        <div class="flex space-x-1 items-center text-red-800">
                            <x-icons.star :filled="$booking->review->service >= 1" class="h-6"/>
                            <x-icons.star :filled="$booking->review->service >= 2" class="h-6"/>
                            <x-icons.star :filled="$booking->review->service >= 3" class="h-6"/>
                            <x-icons.star :filled="$booking->review->service >= 4" class="h-6"/>
                            <x-icons.star :filled="$booking->review->service >= 5" class="h-6"/>
                        </div>
                        <label>Very Good</label>
                    </div>
                </div>
                <div class="md:flex md:justify-between items-center">
                    <h5 class="font-bold">Food/Drink</h5>
                    <div class="flex justify-center items-center space-x-2">
                        <label>Very Poor</label>
                        <div class="flex space-x-1 items-center text-red-800">
                            <x-icons.star :filled="$booking->review->product >= 1" class="h-6"/>
                            <x-icons.star :filled="$booking->review->product >= 2" class="h-6"/>
                            <x-icons.star :filled="$booking->review->product >= 3" class="h-6"/>
                            <x-icons.star :filled="$booking->review->product >= 4" class="h-6"/>
                            <x-icons.star :filled="$booking->review->product >= 5" class="h-6"/>
                        </div>
                        <label>Very Good</label>
                    </div>
                </div>
                <div class="md:flex md:justify-between items-center">
                    <h5 class="font-bold">Cleanliness</h5>
                    <div class="flex justify-center items-center space-x-2">
                        <label>Very Poor</label>
                        <div class="flex space-x-1 items-center text-red-800">
                            <x-icons.star :filled="$booking->review->cleanliness >= 1" class="h-6"/>
                            <x-icons.star :filled="$booking->review->cleanliness >= 2" class="h-6"/>
                            <x-icons.star :filled="$booking->review->cleanliness >= 3" class="h-6"/>
                            <x-icons.star :filled="$booking->review->cleanliness >= 4" class="h-6"/>
                            <x-icons.star :filled="$booking->review->cleanliness >= 5" class="h-6"/>
                        </div>
                        <label>Very Good</label>
                    </div>
                </div>
                <div class="md:flex md:justify-between items-center">
                    <h5 class="font-bold">Overall</h5>
                    <div class="flex justify-center items-center space-x-2">
                        <label>Very Poor</label>
                        <div class="flex space-x-1 items-center text-red-800">
                            <x-icons.star :filled="$booking->review->overall >= 1" class="h-6"/>
                            <x-icons.star :filled="$booking->review->overall >= 2" class="h-6"/>
                            <x-icons.star :filled="$booking->review->overall >= 3" class="h-6"/>
                            <x-icons.star :filled="$booking->review->overall >= 4" class="h-6"/>
                            <x-icons.star :filled="$booking->review->overall >= 5" class="h-6"/>
                        </div>
                        <label>Very Good</label>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

