<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">

</head>

<body class="flex min-h-screen items-center justify-center bg-green-800 bg-opacity-90">
    <x-guest-layout>
        <div
            class="flex flex-col lg:flex-row w-full max-w-4xl shadow-lg rounded-lg overflow-hidden my-6 lg:my-12 bg-white">

            <!-- Left Section - Image -->
            <!-- to flush image to corners / remove spacing: remove p-4 and rounded tags. di ako maka decide ano mas okay xD -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-4 rounded-3xl">
                <img src="{{ asset('images/CanopyLogin2.png') }}" alt="Image"
                    class="w-full h-[300px] lg:h-full object-cover object-center rounded-xl">
            </div>

            <!-- Right Section -->
            <div class="w-full lg:w-1/2 p-4 sm:p-6 lg:p-10 flex flex-col justify-center">
                <p class="subheader text-3xl lg:text-4xl font-bold !text-green-700 mb-2 text-center">Create an account
                </p>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="flex flex-col lg:flex-row gap-2">
                        <!-- First Name -->
                        <div class="w-full">
                            <x-label for="name" value="{{ __('First Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div class="w-full">
                            <x-label for="middle_name" value="{{ __('Middle Name') }}" />
                            <x-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name"
                                :value="old('middle_name')" />
                            @error('middle_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-2">
                        <!-- Last Name -->
                        <div class="w-full">
                            <x-label for="last_name" value="{{ __('Last Name') }}" />
                            <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                                :value="old('last_name')" />
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Suffix -->
                        <div class="w-full">
                            <x-label for="suffix" value="{{ __('Suffix') }}" />
                            <x-input id="suffix" class="block mt-1 w-full" type="text" name="suffix"
                                :value="old('suffix')" />
                            @error('suffix')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <!-- Email -->
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required />
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div x-data="{ show: false }">
                        <!-- Password -->
                        <x-label for="password" value="{{ __('Password') }}" class="text-gray-700" />
                        <div class="relative">
                            <x-input id="password" x-bind:type="show ? 'text' : 'password'"
                                class="block mt-1 w-full rounded-full border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 focus:outline-none pr-10"
                                name="password" required autocomplete="current-password" />

                            <button type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-gray-600 font-medium"
                                @click="show = !show">
                                <span x-text="show ? 'Hide' : 'View'"></span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Confirm Password -->
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}"
                            class="mt-4 text-gray-700" />
                        <div class="relative">
                            <x-input id="password_confirmation" x-bind:type="show ? 'text' : 'password'"
                                class="block mt-1 w-full rounded-full border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 focus:outline-none pr-10"
                                name="password_confirmation" required />

                                <button type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-gray-600 font-medium"
                                @click="show = !show">
                                <span x-text="show ? 'Hide' : 'View'"></span>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />

                                    <div class="ms-2">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' =>
                                                '<a target="_blank" href="' .
                                                route('terms.show') .
                                                '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                                __('Terms of Service') .
                                                '</a>',
                                            'privacy_policy' =>
                                                '<a target="_blank" href="' .
                                                route('policy.show') .
                                                '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                                __('Privacy Policy') .
                                                '</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-4">
                        <!-- After registering,  user is redirected back to the login page -->
                        <div class="mt-1 text-sm text-gray-600 flex justify-center space-x-1">
                            <p>Already registered?</p>
                            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Login</a>
                        </div>
                        <x-button>Register</x-button>
                    </div>
                </form>

            </div>
        </div>
    </x-guest-layout>
</body>

</html>
