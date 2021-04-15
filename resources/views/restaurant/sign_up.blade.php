<x-app-layout>
    <div class="relative">
        <div class="relative bg-cover bg-center" style="background-image: url('{{ asset("img/background.jpg") }}');">
            <div class="py-12 md:py-24 bg-gray-500 bg-opacity-75 text-white">
                <div class="container mx-auto p-6 my-6">
                    <h1 class="text-6xl text-red-800 font-black">Join Us</h1>
                    <p class="md:w-1/2 lg:w-1/3">With your customer service and quality product mixed with our platform and knowledge, the future of hospitality is here.</p>
                </div>
            </div>
        </div>

        <div class="container mx-auto p-3 bg-white shadow-lg">
            @auth
                @livewire("restaurant.sign-up")
            @else
                <p class="my-6">Please <a class="text-red-800 hover:text-red-600" href="{{ route("register") }}">register</a> for an account first, then come back here to complete restaurant sign up. Already have an account? <a class="text-red-800 hover:text-red-600" href="{{ route("login") }}">Sign In</a></p>
            @endauth
        </div>
    </div>
</x-app-layout>
