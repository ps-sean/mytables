<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-mark class="block h-6" />
                    </a>
                </div>

                @auth
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('bookings') }}" :active="request()->routeIs('bookings')">
                            Bookings
                        </x-nav-link>
                        @if(auth()->user()->restaurants->count())
                            <div class="flex items-center">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                            <div>
                                                Restaurants
                                                @if(auth()->user()->restaurantBookingsPending)
                                                    &nbsp;
                                                    <span class="rounded-full bg-red-800 text-white px-2">{{ auth()->user()->restaurantBookingsPending }}</span>
                                                @endif
                                            </div>

                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link href="{{ route('dashboard') }}">
                                            Dashboard
                                        </x-dropdown-link>
                                        <x-dropdown-link href="{{ route('restaurant.bookings_select') }}">
                                            Bookings
                                            @if(auth()->user()->restaurantBookingsPending)
                                                &nbsp;
                                                <span class="rounded-full bg-red-800 text-white px-2">{{ auth()->user()->restaurantBookingsPending }}</span>
                                            @endif
                                        </x-dropdown-link>
                                        <x-dropdown-link href="{{ route('my-restaurants') }}">
                                            Settings
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                        @if(auth()->user()->belongsToTeam(\App\Models\Team::find(1)))
                            <div class="flex items-center">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                            <div>Admin</div>

                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link href="{{ route('restaurant.status', 'pending') }}">
                                            Restaurants
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                    </div>
                @endauth
            </div>

        @auth
            <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-3">
                    @livewire("notifications.toggle")
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Team Management -->
{{--                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && auth()->user()->belongsToTeam(\App\Models\Team::find(1)))--}}
{{--                                <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                                    {{ __('Manage Team') }}--}}
{{--                                </div>--}}

{{--                                <!-- Team Settings -->--}}
{{--                                <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">--}}
{{--                                    {{ __('Team Settings') }}--}}
{{--                                </x-dropdown-link>--}}

{{--                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())--}}
{{--                                    <x-dropdown-link href="{{ route('teams.create') }}">--}}
{{--                                        {{ __('Create New Team') }}--}}
{{--                                    </x-dropdown-link>--}}
{{--                                @endcan--}}

{{--                                <div class="border-t border-gray-100"></div>--}}

{{--                                <!-- Team Switcher -->--}}
{{--                                <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                                    {{ __('Switch Teams') }}--}}
{{--                                </div>--}}

{{--                                @foreach (Auth::user()->allTeams() as $team)--}}
{{--                                    <x-switchable-team :team="$team" />--}}
{{--                                @endforeach--}}

{{--                                <div class="border-t border-gray-100"></div>--}}
{{--                            @endif--}}

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                                     onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                    {{ __('Logout') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden sm:flex sm:ml-6 space-x-8">
                    <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                        Log In
                    </x-nav-link>
                    <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                        Register
                    </x-nav-link>
                </div>
        @endauth

        <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden space-x-4">
                @auth
                    @livewire("notifications.toggle")
                @endauth
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    @auth
        <x-responsive-nav-link href="{{ route('bookings') }}" :active="request()->routeIs('bookings')">
            Bookings
        </x-responsive-nav-link>
        @if(auth()->user()->restaurants->count())
            <div class="pt-2 pb-3 space-y-1 border-t border-gray-200">
                <h5 class="px-4 font-bold text-gray-600">Restaurants</h5>
                @if(auth()->user()->restaurants->count())
                    <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('restaurant.bookings_select') }}" :active="request()->routeIs('restaurant.bookings_select')">
                        Bookings
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('my-restaurants') }}" :active="request()->routeIs('my-restaurants')">
                        Settings
                    </x-responsive-nav-link>
                @endif
            </div>
        @endif
        @if(auth()->user()->belongsToTeam(\App\Models\Team::find(1)))
            <div class="pt-2 pb-3 space-y-1 border-t border-gray-200">
                <h5 class="px-4 font-bold text-gray-600">Admin</h5>
                <x-responsive-nav-link href="{{ route('restaurant.status', 'pending') }}" :active="request()->routeIs('restaurant.status')">
                    Restaurants
                </x-responsive-nav-link>
            </div>
        @endif
        <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>

                    <div class="ml-3">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif

                <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                            {{ __('Logout') }}
                        </x-responsive-nav-link>
                    </form>

                    <!-- Team Management -->
{{--                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())--}}
{{--                        <div class="border-t border-gray-200"></div>--}}

{{--                        <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                            {{ __('Manage Team') }}--}}
{{--                        </div>--}}

{{--                        <!-- Team Settings -->--}}
{{--                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">--}}
{{--                            {{ __('Team Settings') }}--}}
{{--                        </x-responsive-nav-link>--}}

{{--                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">--}}
{{--                            {{ __('Create New Team') }}--}}
{{--                        </x-responsive-nav-link>--}}

{{--                        <div class="border-t border-gray-200"></div>--}}

{{--                        <!-- Team Switcher -->--}}
{{--                        <div class="block px-4 py-2 text-xs text-gray-400">--}}
{{--                            {{ __('Switch Teams') }}--}}
{{--                        </div>--}}

{{--                        @foreach (Auth::user()->allTeams() as $team)--}}
{{--                            <x-switchable-team :team="$team" component="jet-responsive-nav-link" />--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
