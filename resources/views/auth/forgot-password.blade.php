<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
</head>

<body class="bg-green-100">
    <x-auth-layout>
        {{-- <x-authentication-card>
            <x-slot name="logo">
                <img src="{{ asset('images/canopy-logo.png') }}" class="h-24 w-24" />
            </x-slot>

            <div class="mb-4 font-semibold text-xl text-gray-800 leading-tight text-center">
                {{ __('Find Your Account') }}
            </div>

            <div class="mb-4 text-sm text-gray-600">
                {{ __('Please enter your email to find your account. We will send a link to reset your password!') }}
            </div>

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="block">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                </div>

                <div class="flex items-center justify-center mt-8">
                    <x-button>
                        {{ __('Email Password Reset Link') }}
                    </x-button>
                </div>
            </form>
        </x-authentication-card> --}}
        <!-- Form -->
        <div class="relative z-10 p-4 flex items-center justify-center min-h-screen">
            <div class="flex flex-col items-center p-8 bg-white bg-opacity-90 rounded-3xl shadow-xl w-full max-w-sm">

                <!-- Logo -->
                <div class="relative -mt-20">
                    <div class="rounded-full bg-white p-2 shadow-md">
                        <img src="{{ asset('images/canopy-logo.png') }}" class="h-24 w-24" />
                    </div>
                </div>

                <p class="text-xl lg:text-3xl font-bold text-green-700 mt-3">
                    Find Your Account
                </p>

                <div class="text-md text-gray-700 mt-2">
                    Please enter your email to find your account. We will send a link to reset your password!
                </div>

                <x-validation-errors class="mb-4" />

                @if (session('status'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-700 text-white px-6 py-3 rounded shadow-lg z-50"
                        style="min-width: 250px;">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="w-full flex flex-col items-center justify-center">

                    <form method="POST" action="{{ route('password.email') }}" class="w-full space-y-6">
                        @csrf

                        <!-- Email -->
                        <div class="relative">
                            <input id="email"
                                class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" placeholder="Email" />

                            <!-- icon -->
                            <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                                <i class="fa-solid fa-envelope text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Send Button -->
                        <div class="flex justify-center">
                            <button type="submit"
                                class="w-full flex items-center mt-1 justify-center py-3 px-6 text-xs rounded-full uppercase tracking-widest text-white font-bold bg-green-800 hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                Email Password Reset Link
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </x-auth-layout>
</body>

</html>
