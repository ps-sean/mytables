<div class="bg-white">
    @if($reviews->count() < 1)
        <div class="py-6">
            <p class="text-center">This restaurant has not been reviewed yet.</p>
        </div>
    @else
        @foreach($reviews as $review)
            <div class="grid md:grid-cols-2 gap-4 p-6 border-b">
                <div class="space-y-2">
                    <h5 class="font-bold text-xl">{{ $review->title }}</h5>
                    <p>{{ $review->review }}</p>
                    <p class="text-gray-600 text-sm">{{ $review->user ?? "Anonymous" }} (Booking #{{ $review->booking->id }} on {{ $review->booking->booked_at->toDayDateTimeString() }})</p>
                </div>
                <div class="space-y-4">
                    <h5 class="font-bold text-xl">Ratings</h5>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Price</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <x-icons.star :filled="$review->price >= 1" class="h-6"/>
                                <x-icons.star :filled="$review->price >= 2" class="h-6"/>
                                <x-icons.star :filled="$review->price >= 3" class="h-6"/>
                                <x-icons.star :filled="$review->price >= 4" class="h-6"/>
                                <x-icons.star :filled="$review->price >= 5" class="h-6"/>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Service</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <x-icons.star :filled="$review->service >= 1" class="h-6"/>
                                <x-icons.star :filled="$review->service >= 2" class="h-6"/>
                                <x-icons.star :filled="$review->service >= 3" class="h-6"/>
                                <x-icons.star :filled="$review->service >= 4" class="h-6"/>
                                <x-icons.star :filled="$review->service >= 5" class="h-6"/>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Food/Drink</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <x-icons.star :filled="$review->product >= 1" class="h-6"/>
                                <x-icons.star :filled="$review->product >= 2" class="h-6"/>
                                <x-icons.star :filled="$review->product >= 3" class="h-6"/>
                                <x-icons.star :filled="$review->product >= 4" class="h-6"/>
                                <x-icons.star :filled="$review->product >= 5" class="h-6"/>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Cleanliness</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <x-icons.star :filled="$review->cleanliness >= 1" class="h-6"/>
                                <x-icons.star :filled="$review->cleanliness >= 2" class="h-6"/>
                                <x-icons.star :filled="$review->cleanliness >= 3" class="h-6"/>
                                <x-icons.star :filled="$review->cleanliness >= 4" class="h-6"/>
                                <x-icons.star :filled="$review->cleanliness >= 5" class="h-6"/>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                    <div class="md:flex md:justify-between items-center">
                        <h5 class="font-bold">Overall</h5>
                        <div class="flex justify-center items-center space-x-2">
                            <label>Very Poor</label>
                            <div class="flex space-x-1 items-center text-red-800">
                                <x-icons.star :filled="$review->overall >= 1" class="h-6"/>
                                <x-icons.star :filled="$review->overall >= 2" class="h-6"/>
                                <x-icons.star :filled="$review->overall >= 3" class="h-6"/>
                                <x-icons.star :filled="$review->overall >= 4" class="h-6"/>
                                <x-icons.star :filled="$review->overall >= 5" class="h-6"/>
                            </div>
                            <label>Very Good</label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="px-6 py-3">
            {!! $reviews->links() !!}
        </div>
    @endif
</div>
