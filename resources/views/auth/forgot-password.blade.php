<x-guest-layout>
    <div class="mb-6 text-sm !text-white">
        {{ __('Forgot your password? No problem. Just enter your email, and we will send you a reset link.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="!text-white text-lg font-semibold mb-2 block" />
            <x-text-input id="email" 
                class="block w-full px-4 py-3 text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <x-primary-button class="w-full py-3 text-lg">
                {{ __('Send Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
