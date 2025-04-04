<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="!text-white text-lg font-semibold mb-2 block" />
            <x-text-input id="name" 
                class="block w-full px-4 py-3 text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="!text-white text-lg font-semibold mb-2 block" />
            <x-text-input id="email" 
                class="block w-full px-4 py-3 text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="!text-white text-lg font-semibold mb-2 block" />
            <x-text-input id="password" 
                class="block w-full px-4 py-3 text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="password" 
                name="password" 
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="!text-white text-lg font-semibold mb-2 block" />
            <x-text-input id="password_confirmation" 
                class="block w-full px-4 py-3 text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="password" 
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <!-- Links & Button -->
        <div class="flex flex-col sm:flex-row sm:justify-between mt-4 space-y-3 sm:space-y-0">
            <a class="text-sm text-blue-400 hover:text-blue-300 transition" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>

        <div class="flex justify-center">
            <x-primary-button class="w-full py-3 text-lg">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
