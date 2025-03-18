<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="!text-white text-lg font-semibold mb-2 block" />
            <x-text-input id="email" 
                class="block w-full px-4 py-3 text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="!text-white text-lg font-semibold mb-2 block" />
            <x-text-input id="password" 
                class="block w-full px-4 py-3 text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="password" 
                name="password" 
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center space-x-2">
            <input id="remember_me" type="checkbox" class="rounded border-gray-500 text-indigo-600 focus:ring-indigo-500" name="remember">
            <label for="remember_me" class="!text-white text-sm">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Links -->
        <div class="flex flex-col sm:flex-row sm:justify-between mt-4 space-y-3 sm:space-y-0">
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-400 hover:text-blue-300 transition" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <a class="text-sm text-blue-400 hover:text-blue-300 transition" href="{{ route('register') }}">
                {{ __('Does not yet have an account?') }}
            </a>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <x-primary-button class="w-full py-3 text-lg">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
