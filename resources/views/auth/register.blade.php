<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        <div class="flex flex-col items-center mb-12">
            <div class="relative w-28 h-28 mb-4">
                <div class="absolute inset-0 bg-blue-600/30 blur-2xl rounded-full"></div>
                <svg viewBox="0 0 100 100" class="relative text-blue-500 drop-shadow-[0_0_20px_rgba(59,130,246,0.6)]">
                    <path d="M25,20 C25,10 75,10 75,20" stroke="currentColor" stroke-width="5" fill="none" stroke-linecap="round"/>
                    <path d="M15,30 L85,30 L75,80 L25,80 Z" fill="currentColor" fill-opacity="0.1" stroke="currentColor" stroke-width="4" stroke-linejoin="round"/>
                    <line x1="35" y1="30" x2="40" y2="80" stroke="currentColor" stroke-width="3"/>
                    <line x1="65" y1="30" x2="60" y2="80" stroke="currentColor" stroke-width="3"/>
                    <circle cx="50" cy="55" r="8" fill="currentColor" class="animate-pulse"/>
                </svg>
            </div>
        </div>
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
