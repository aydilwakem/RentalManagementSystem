<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">

</head>
<x-guest-layout>

    <body
        class="bg-green-800 text-black p-6 lg:p-8 lg:justify-center flex-col flex items-center justify-center bg-opacity-90 min-h-screen">

        <div class="p-4">
            <div
                class="flex flex-col lg:flex-row w-full max-w-4xl shadow-lg rounded-lg overflow-hidden my-6 lg:my-12 bg-white">

                <!--- Left Section - Icon Version
                <div class="w-1/2 flex items-center justify-center bg-green-600 bg-opacity-10">
                    <div class="bg-white/80 p-10 rounded-full flex items-center justify-center w-24 h-24">
                        <i class="fas fa-house text-primary text-6xl"></i>
                    </div>
                </div> -->

                <!-- Left Section - Image -->
                <!-- to flush image to corners / remove spacing: remove p-4 and rounded tags. di ako maka decide ano mas okay xD -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-4 rounded-3xl">
                    <img src="{{ asset('images/CanopyLogin.png') }}" alt="Image"
                        class="w-full h-[300px] lg:h-full object-cover object-center rounded-xl">
                </div>


                <!-- Right Section - Login Form -->
                <div class="w-full lg:w-1/2 p-6 lg:p-10 flex flex-col justify-center">
                    <p class="subheader text-2xl lg:text-4xl font-bold !text-green-700 mb-2 text-center">Welcome back!
                    </p>

                    <x-validation-errors class="mb-4" />

                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-label for="email" value="{{ __('Email') }}" class="text-gray-700" />
                            <x-input id="email"
                                class="block mt-1 w-full rounded-full border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 focus:outline-none"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" />
                        </div>

                        <div x-data="{ show: false }">
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
                        </div>


                        <div class="flex flex-col lg:flex-row items-center justify-between mt-4">
                            <div class="flex items-center">
                                <x-checkbox id="remember_me" name="remember" class="text-green-600" />
                                <label for="remember_me" class="ms-2 text-sm text-gray-600">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-gray-600 hover:underline mt-2 lg:mt-0"
                                    href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        <x-button
                            class="bg-green-800 bg-opacity-85 hover:bg-green-800 flex text-white text-center text-md justify-center font-bold w-full py-2 px-6 rounded-full">
                            Log in
                        </x-button>
                    </form>

                    <div class="mt-4 text-sm text-gray-600 flex justify-center space-x-1">
                        <p>Don't have an account?</p>
                        <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Sign up</a>
                    </div>

                </div>
            </div>
        </div>



        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</x-guest-layout>

</html>
