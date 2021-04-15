@props(["clicking" => true, "restaurant" => null])

<div @if($clicking) x-data="{ open: false }" @endif>
    <div x-show.transition.in="!open" class="text-center cursor-pointer" x-on:click="open = true">
        @if($r = $restaurant->averageReview("overall"))
            <p class="flex items-baseline"><span class="text-xl font-bold">{{ $r }}</span><span class="text-gray-600">&nbsp;/&nbsp;5</span></p>
        @else
            <p>No Reviews</p>
        @endif
    </div>
    <div x-cloak x-show.transition.in="open" class="cursor-pointer" x-on:click="open = false">
        <div class="flex justify-between items-center space-x-4">
            <h5 class="font-bold text-lg">Overall</h5>
            @if($r = $restaurant->averageReview("overall"))
                <p class="flex items-baseline"><span class="text-xl font-bold">{{ $r }}</span><span class="text-gray-600">&nbsp;/&nbsp;5</span></p>
            @else
                <p>No Reviews</p>
            @endif
        </div>
        <div class="flex justify-between items-center space-x-4">
            <h5 class="font-bold text-lg">Price</h5>
            @if($r = $restaurant->averageReview("price"))
                <p class="flex items-baseline"><span class="text-xl font-bold">{{ $r }}</span><span class="text-gray-600">&nbsp;/&nbsp;5</span></p>
            @else
                <p>No Reviews</p>
            @endif
        </div>
        <div class="flex justify-between items-center space-x-4">
            <h5 class="font-bold text-lg">Service</h5>
            @if($r = $restaurant->averageReview("service"))
                <p class="flex items-baseline"><span class="text-xl font-bold">{{ $r }}</span><span class="text-gray-600">&nbsp;/&nbsp;5</span></p>
            @else
                <p>No Reviews</p>
            @endif
        </div>
        <div class="flex justify-between items-center space-x-4">
            <h5 class="font-bold text-lg">Food/Drink</h5>
            @if($r = $restaurant->averageReview("product"))
                <p class="flex items-baseline"><span class="text-xl font-bold">{{ $r }}</span><span class="text-gray-600">&nbsp;/&nbsp;5</span></p>
            @else
                <p>No Reviews</p>
            @endif
        </div>
        <div class="flex justify-between items-center space-x-4">
            <h5 class="font-bold text-lg">Cleanliness</h5>
            @if($r = $restaurant->averageReview("cleanliness"))
                <p class="flex items-baseline"><span class="text-xl font-bold">{{ $r }}</span><span class="text-gray-600">&nbsp;/&nbsp;5</span></p>
            @else
                <p>No Reviews</p>
            @endif
        </div>
    </div>

</div>
