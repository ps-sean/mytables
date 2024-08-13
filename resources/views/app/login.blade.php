<x-frame-app-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        {{ session("_token") }}

        <form method="POST" action="{{ route('app.login') }}">
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="text-red-600 hidden py-4" id="error_container">

            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4 bg-red-800 hover:bg-red-700 text-white">
                    {{ __('Login') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-frame-app-layout>
