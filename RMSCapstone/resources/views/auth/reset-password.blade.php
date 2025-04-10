<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                    :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div x-data="{ show: false }">
                <!-- Password -->
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-700" />
                <div class="relative">
                    <x-input id="password" x-bind:type="show ? 'text' : 'password'"
                        class="block mt-1 w-full rounded-full border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 focus:outline-none pr-10"
                        name="password" required autocomplete="current-password" />

                    <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600"
                        @click="show = !show">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
                @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Confirm Password -->
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="mt-4 text-gray-700" />
                <div class="relative">
                    <x-input id="password_confirmation" x-bind:type="show ? 'text' : 'password'"
                        class="block mt-1 w-full rounded-full border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 focus:outline-none pr-10"
                        name="password_confirmation" required />

                    <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600"
                        @click="show = !show">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
                @error('password_confirmation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Button --}}
            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>