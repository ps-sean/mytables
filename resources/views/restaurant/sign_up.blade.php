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

        <div class="container mx-auto p-10 bg-white shadow-lg space-y-10">
            @auth
                @livewire("restaurant.sign-up")
            @else
                <p class="my-6">Please <a class="text-red-800 hover:text-red-600" href="{{ route("register") }}">register</a> for an account first, then come back here to complete restaurant sign up. Already have an account? <a class="text-red-800 hover:text-red-600" href="{{ route("login") }}">Sign In</a></p>
            @endauth

            <div class="space-y-5">
                <div class="bg-red-800 -mx-10 py-2 px-10">
                    <h2 class="font-bold text-3xl text-white">Pricing</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="border-gray-200 shadow-lg p-10 rounded space-y-10">
                        <div class="flex flex-col items-center justify-center">
                            <h6 class="font-bold text-6xl text-red-800 -mb-5">£5</h6>
                            <p>Per Table / Month</p>
                        </div>
                        <ul class="list-disc list-inside text-center">
                            <li>Pay per physical table, not per booking or per cover</li>
                            <li>Only pay for days you're live</li>
                            <li>Only pay for tables that customers can book</li>
                            <li>Ability to add temporary tables and only pay for active days</li>
                            <li>No contract!</li>
                        </ul>
                    </div>
                    <div class="md:col-span-2 space-y-5">
                        <p>
                            Our pricing is designed to be scalable but fixed. We don't punish smaller restaurants and we
                            don't punish you for using our system.
                        </p>
                        <p>
                            We have no hidden fees, and we'll explain any alterations to your monthly payments and allow
                            you to track any changes.
                        </p>
                        <p>
                            We don't charge you per booking or per cover, we only charge for the tables you add to our
                            system that your customers can actually book; and only at times they can actually be booked.
                        </p>
                        <p>
                            Just want to use our system as your personal diary? That's fine by us, if your tables aren't
                            available to customers to book online, you don't pay anything.
                        </p>
                        <p>
                            We don't want to tie you down to any contracts and force you to use our system, that's why
                            you only pay for what you've used and you're free to leave any time.
                        </p>
                        <p>
                            If you would like more information on our pricing or have questions, please contact
                            <a class="text-red-800 hover:text-red-700 transition-all duration-150 ease-in-out"
                               href="mailtto:info@mytables.co.uk">info@mytables.co.uk</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
