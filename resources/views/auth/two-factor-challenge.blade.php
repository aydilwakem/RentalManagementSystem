<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Two Factor Login</title>
</head>

<body
    class="bg-green-100 text-black lg:justify-center flex-col flex items-center justify-center bg-opacity-90 min-h-screen">
    <x-auth-layout>

        <div class="relative z-10 p-4 flex items-center justify-center min-h-screen">
            <div class="flex flex-col items-center p-8 bg-white bg-opacity-90 rounded-3xl shadow-xl w-full max-w-sm">

                <!-- Logo -->
                <div class="relative -mt-20">
                    <div class="rounded-full bg-white p-2 shadow-md">
                        <img src="{{ asset('images/canopy-logo.png') }}" class="h-24 w-24" />
                    </div>
                </div>

                <p class="text-xl lg:text-2xl font-bold text-green-700 mt-3">
                    Two-Factor Authentication
                </p>

                <div x-data="{ recovery: false }">
                    <div class="mb-4 text-sm mt-4 text-gray-600" x-show="! recovery">
                        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                    </div>

                    <div class="mb-4 text-sm mt-4 text-gray-600" x-cloak x-show="recovery">
                        {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                    </div>

                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('two-factor.login') }}">
                        @csrf

                        {{-- <div class="mt-4" x-show="! recovery">
                            <x-label for="code" value="{{ __('Code') }}" />
                            <x-input id="code" class="block mt-1 w-full" type="text" inputmode="numeric"
                                name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                        </div> --}}

                        <div class="relative" x-show="! recovery">
                            <label for="code" class="block mb-1 text-sm font-medium text-gray-700">Code</label>
                            <input id="code"
                                class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                type="text" inputmode="numeric"
                                name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                            <!-- icon -->
                            <div class="absolute top-11 left-0 flex items-center pl-5">
                                <i class="fa-solid fa-shield-halved text-gray-400"></i>
                            </div>
                        </div>

                        {{-- <div class="mt-4" x-cloak x-show="recovery">
                            <x-label for="recovery_code" value="{{ __('Recovery Code') }}" />
                            <x-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code"
                                x-ref="recovery_code" autocomplete="one-time-code" />
                        </div> --}}
                        <div class="relative" x-show="recovery">
                            <label for="recovery_code" class="block mb-1 text-sm font-medium text-gray-700">Recovery Code</label>
                            <input for="recovery_code"
                                class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                            <!-- icon -->
                            <div class="absolute top-11 left-0 flex items-center pl-5">
                                <i class="fa-solid fa-shield-halved text-gray-400"></i>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <button type="button"
                                class="text-sm text-gray-600 hover:text-green-700 hover:underline hover:font-medium cursor-pointer"
                                x-show="! recovery"
                                x-on:click="
                                            recovery = true;
                                            $nextTick(() => { $refs.recovery_code.focus() })
                                        ">
                                {{ __('Use a recovery code') }}
                            </button>

                            <button type="button"
                                class="text-sm text-gray-600 hover:text-green-700 hover:underline hover:font-medium cursor-pointer" x-cloak
                                x-show="recovery"
                                x-on:click="
                                            recovery = false;
                                            $nextTick(() => { $refs.code.focus() })
                                        ">
                                {{ __('Use an authentication code') }}
                            </button>

                            <x-button class="ms-4">
                                {{ __('Log in') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-auth-layout>
</body>

</html>
