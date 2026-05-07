<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>

<body class="bg-green-100">

    <x-auth-layout>
        {{-- <x-authentication-card>
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

                <!-- Send Button -->
                <div class="flex items-center justify-end mt-4">
                    <x-button>
                        {{ __('Reset Password') }}
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
                    Update Your Password
                </p>

                <div class="text-md text-gray-700 mt-2 text-center">
                    Choose a new password and confirm it below to update your account credentials
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
                        class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50"
                        style="min-width: 250px;">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="w-full flex flex-col items-center justify-center">

                    <form method="POST" action="{{ route('password.update') }}" class="w-full space-y-4">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div class="relative">
                            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                            <input id="email"
                                class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                type="email" name="email" value="{{ old('email', $request->email) }}" required
                                autofocus autocomplete="username" placeholder="Email" />
                            <!-- icon -->
                            <div class="absolute top-11 left-0 flex items-center pl-5">
                                <i class="fa-solid fa-envelope text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                            <div class="relative">
                                <input id="password" type="password"
                                    class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    name="password" required autocomplete="current-password" placeholder="Password" />

                                <!-- left icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                                    <i class="fa-solid fa-lock text-gray-400"></i>
                                </div>

                                <!-- Toggle button -->
                                <div class="absolute inset-y-0 right-0 flex items-center pr-5">
                                    <i id="togglePassword" class="fa-solid fa-eye text-gray-400 cursor-pointer"></i>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <label for="password_confirmation"
                                class="block mt-5 mb-1 text-sm font-medium text-gray-700">Confirm Password</label>
                            <div class="relative">
                                <input id="password_confirmation" type="password"
                                    class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                    name="password_confirmation" required placeholder="Confirm Password" />

                                <!-- left icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                                    <i class="fa-solid fa-lock text-gray-400"></i>
                                </div>

                                <!-- Toggle button -->
                                <div class="absolute inset-y-0 right-0 flex items-center pr-5">
                                    <i id="toggleConfirmPassword" class="fa-solid fa-eye text-gray-400 cursor-pointer"></i>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Generic toggle function
                            function setupPasswordToggle(inputId, toggleId) {
                                const input = document.getElementById(inputId);
                                const toggle = document.getElementById(toggleId);

                                // Show toggle only when typing
                                input.addEventListener("input", () => {
                                    toggle.style.display = input.value ? "block" : "none";
                                });

                                // Toggle password visibility
                                toggle.addEventListener("click", function() {
                                    const isPassword = input.type === "password";
                                    input.type = isPassword ? "text" : "password";
                                    this.classList.toggle("fa-eye");
                                    this.classList.toggle("fa-eye-slash");
                                });
                            }

                            // Apply to both fields
                            setupPasswordToggle("password", "togglePassword");
                            setupPasswordToggle("password_confirmation", "toggleConfirmPassword");
                        </script>


                        <!-- Send Button -->
                        <div class="flex justify-center">
                            <button type="submit"
                                class="w-full flex items-center mt-1 justify-center py-3 px-6 text-xs rounded-full uppercase tracking-widest text-white font-bold bg-green-800 hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                Reset Password
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </x-auth-layout>
    {{-- <script>
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        togglePassword.addEventListener('click', function() {
            const isPassword = password.type === 'password';
            password.type = isPassword ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Confirm password toggle
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const passwordConfirmation = document.getElementById('password_confirmation');
        toggleConfirmPassword.addEventListener('click', function() {
            const isPassword = passwordConfirmation.type === 'password';
            passwordConfirmation.type = isPassword ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script> --}}
</body>

</html>
