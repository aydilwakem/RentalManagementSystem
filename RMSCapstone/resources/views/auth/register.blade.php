<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">

</head>

{{-- <x-auth-layout>

    <body class="flex min-h-screen items-center justify-center bg-green-800 bg-opacity-90">
        {{-- <!-- Image Background -->
    <div class="absolute inset-0 z-0">
        <div class="w-full h-full bg-cover bg-center filter blur-sm brightness-95"
            style="background-image: url('{{ asset('images/canopy-login2.png') }}');">
        </div>
    </div> -
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
                        <x-input id="email" class="block mt-1 w-full" type="email"
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,5}$" name="email" :value="old('email')"
                            required />
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
                                name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                required autocomplete="current-password" />

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
                                                '"
                                                                                                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                                __('Terms of Service') .
                                                '</a>',
                                            'privacy_policy' =>
                                                '<a target="_blank" href="' .
                                                route('policy.show') .
                                                '"
                                                                                                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
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
    </body>
</x-auth-layout> --}}

<x-auth-layout>

    <body class="relative min-h-screen bg-green-800 text-black overflow-hidden">

        <!-- Image Background -->
        <div class="absolute inset-0 z-0">
            <div class="w-full h-full bg-cover bg-center filter blur-sm brightness-95"
                style="background-image: url('{{ asset('images/canopy-login2.png') }}');">
            </div>
        </div>

        <!-- Form -->
        <div class="relative z-10 flex items-center justify-center min-h-screen">
            <div class="flex flex-col items-center p-6 bg-white bg-opacity-90 rounded-3xl shadow-xl w-full max-w-lg">

                <!-- Logo -->
                <div class="relative -mt-20">
                    <div class="rounded-full bg-white p-2 shadow-md mt-8">
                        <img src="{{ asset('images/canopy-logo.png') }}" class="h-24 w-24" />
                    </div>
                </div>

                <p class="subheader text-2xl lg:text-4xl font-bold !text-green-700 text-center mt-1">
                    Create Account
                </p>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <div class="w-full flex flex-col items-center justify-center">

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-2">
                        @csrf

                        <div class="flex flex-col lg:flex-row gap-2">
                            {{-- <!-- First Name -->
                            <div class="w-full">
                                <x-label for="name" value="{{ __('First Name') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required autofocus />
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div> --}}

                            <!-- First Name -->
                            <div class="relative">
                                <label for="name" class=" mt-5 text-sm font-medium text-gray-700 flex-col ">First
                                    Name <span class="text-red-500">*</span></label>
                                <input id="name"
                                    class="block w-full py-2.5 px-12 mt-1 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    type="text" name="name" :value="old('name')" required autofocus
                                    placeholder="Ex. Juan" />

                                <!-- icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5 mt-7">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Middle Name -->
                            {{-- <div class="w-full">
                                <x-label for="middle_name" value="{{ __('Middle Name') }}" />
                                <x-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name"
                                    :value="old('middle_name')" />
                                @error('middle_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div> --}}
                            <div class="relative">
                                <label for="middle_name"
                                    class=" mt-5 mb-1 text-sm font-medium text-gray-700 flex-col">Middle Name</label>
                                <input id="middle_name"
                                    class="block w-full py-2.5 px-12 mt-1 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    type="text" name="middle_name" :value="old('middle_name')" required autofocus
                                    placeholder="Ex. Mercado" />

                                <!-- icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5 mt-7">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row gap-2">
                            <!-- Last Name -->
                            {{-- <div class="w-full">
                                <x-label for="last_name" value="{{ __('Last Name') }}" />
                                <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                                    :value="old('last_name')" />
                                @error('last_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div> --}}
                            <div class="relative">
                                <label for="last_name"
                                    class=" mt-5 mb-1 text-sm font-medium text-gray-700 flex-col">Last Name <span
                                        class="text-red-500">*</span></label>
                                <input id="last_name"
                                    class="block w-full py-2.5 px-12 mt-1 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    type="text" name="last_name" :value="old('last_name')" required autofocus
                                    placeholder="Ex. Dela Cruz" />

                                <!-- icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5 mt-7">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Suffix -->
                            {{-- <div class="w-full">
                                <x-label for="suffix" value="{{ __('Suffix') }}" />
                                <x-input id="suffix" class="block mt-1 w-full" type="text" name="suffix"
                                    :value="old('suffix')" />
                                @error('suffix')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div> --}}
                            <div class="relative">
                                <label for="suffix"
                                    class=" mt-5 mb-1 text-sm font-medium text-gray-700 flex-col">Suffix</label>
                                <input id="suffix"
                                    class="block w-full py-2.5 px-12 mt-1 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    type="text" name="suffix" :value="old('suffix')" required autofocus
                                    placeholder="Ex. Jr." />

                                <!-- icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5 mt-7">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        {{-- <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" class="block mt-1 w-full" type="email"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,5}$" name="email" :value="old('email')"
                                required />
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <div class="relative">
                            <label for="email" class=" mt-5 mb-1 text-sm font-medium text-gray-700 flex-col">Email
                                <span class="text-red-500">*</span></label>
                            <input id="email"
                                class="block w-full py-2.5 px-12 mt-1 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                type="email" name="email" :value="old('email')" required autofocus
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,5}$" placeholder="Ex. Mercado" />

                            <!-- icon -->
                            <div class="absolute inset-y-0 left-0 flex items-center pl-5 mt-7">
                                <i class="fa-solid fa-envelope text-gray-400"></i>
                            </div>
                        </div>


                        <!-- Password -->
                        <div x-data="{ show: false }">
                            <label for="password" class="mt-5 mb-1 text-sm font-medium text-gray-700 flex-col">
                                Password
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" x-bind:type="show ? 'text' : 'password'"
                                    class="block w-full py-2.5 px-12 mt-1 mb-2 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                    required autocomplete="current-password"
                                    placeholder="Password (8+ chars with letter, number, symbol)" />
                                <!-- icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                                    <i class="fa-solid fa-lock text-gray-400"></i>
                                </div>

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
                            <label for="password_confirmation"
                                class="mt-8 mb-1 text-sm font-medium text-gray-700 flex-col">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" x-bind:type="show ? 'text' : 'password'"
                                    class="block w-full py-2.5 px-12 mt-1 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    name="password_confirmation" required placeholder="Confirm your password" />
                                <!-- icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                                    <i class="fa-solid fa-lock text-gray-400"></i>
                                </div>

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
                            <div class="mt-5">
                                <x-label for="terms">
                                    <div class="flex items-center">
                                        <input id="terms" name="terms" type="checkbox" required
                                        class="rounded text-green-500 focus:ring-green-500 border-gray-300" />

                                        <div class="ms-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' =>
                                                    '<a target="_blank" href="' .
                                                    route('terms.show') .
                                                    '"
                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">' .
                                                    __('Terms of Service') .
                                                    '</a>',
                                                'privacy_policy' =>
                                                    '<a target="_blank" href="' .
                                                    route('policy.show') .
                                                    '"
                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">' .
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
                                <a href="{{ route('login') }}"
                                    class="text-primary font-semibold hover:underline hover:text-green-600">Login</a>
                            </div>
                            <x-button>Register</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

    </body>
    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', function() {
            const isPassword = password.type === 'password';
            password.type = isPassword ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</x-auth-layout>

</html>
