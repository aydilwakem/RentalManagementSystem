<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex flex-col lg:flex-row rounded-3xl overflow-hidden">

                <!-- Left Section - Image -->
                <div class="w-full lg:w-1/2 h-[250px] lg:h-auto">
                    <img src="{{ asset('images/pool-house1.jpg') }}" alt="Image"
                        class="w-full h-full object-cover object-center">
                </div>

                <!-- Right Section - Login Form -->
                <div class="w-full lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center bg-white">
                    <p class="text-2xl lg:text-4xl font-bold text-green-700 mb-4 text-center">Welcome back!</p>

                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" class="text-gray-700" />
                            <x-input id="email"
                                class="block mt-1 w-full rounded-full border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 focus:outline-none"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" />
                        </div>

                        <div>
                            <x-label for="password" value="{{ __('Password') }}" class="text-gray-700" />
                            <x-input id="password"
                                class="block mt-1 w-full rounded-full border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-300 focus:outline-none"
                                type="password" name="password" required autocomplete="current-password" />
                        </div>

                        <div class="flex flex-col lg:flex-row items-center justify-between">
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
        </x-slot>
    </x-authentication-card>
</x-guest-layout>
